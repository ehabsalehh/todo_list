<?php

namespace App\Services;

use App\Exceptions\ApiException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskService
{
    public function index(Request $request)
    {
        if ($request->filled('format') && $request->input('format') === 'csv') {
            $tasks = $this->exportCSV();
        } else {
            $tasks = $this->readTasksFromJSON();
        }
        if ($request->filled('sort')) {
            $sortOrder = $request->get('sort', 'asc');
            usort($tasks, function ($task1, $task2) use ($sortOrder) {
                return $sortOrder === 'asc' ?
                    strcmp($task1['date_added'], $task2['date_added']) :
                    strcmp($task2['date_added'], $task1['date_added']);
            });
        }

        if ($request->filled('filter')) {
            $filterKeyword = $request->get('filter', '');

            $tasks = array_values(array_filter($tasks, function ($task) use ($filterKeyword) {
                return strpos($task['title'], $filterKeyword) !== false;
            }));
        }
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
        ]);


        $tasks = $this->readTasksFromJSON();
        $maxId = 0;

        foreach ($tasks as $task) {
            if ($task['id'] > $maxId) {
                $maxId = $task['id'];
            }
        }
        $task = [
            'id' => $maxId + 1, // Assign the new "id"
            'title' => $request->input('title'),
            'date_added' => now()->toDateTimeString(),
        ];
        $tasks[] = $task;

        $this->writeTasksToJSON($tasks);
        return response()->json(['message' => __('messages.task_added_success')]);

    }

    public function update(Request $request, $taskId)
    {
        $request->validate([
            'title' => 'required|string|max:100',
        ]);

        $tasks = $this->readTasksFromJSON();
        $taskKey = $this->findTaskKeyById($tasks, $taskId);
        if ($tasks[$taskKey]) {
            $tasks[$taskKey]['title'] = $request->input('title');
            $this->writeTasksToJSON($tasks);
            return response()->json(['message' => __('messages.task_edited_success')]);
        }
        throw new ApiException("Task not found");

    }

    public function destroy($taskId)
    {
        $tasks = $this->readTasksFromJSON();
        $taskKey = $this->findTaskKeyById($tasks, $taskId);
        if ($tasks[$taskKey]) {
            unset($tasks[$taskKey]);
            $this->writeTasksToJSON($tasks);

            return response()->json(['message' => __('messages.task_deleted_success')]);
        }

        throw new ApiException("Task not found");
    }

    private function readTasksFromJSON()
    {
        $jsonContent = Storage::disk('local')->get('tasks.json');
        return json_decode($jsonContent, true) ?: [];
    }

    private function writeTasksToJSON($tasks)
    {
        $jsonContent = json_encode($tasks);
        Storage::disk('local')->put('tasks.json', $jsonContent);
    }

    private function findTaskKeyById($tasks, $taskId)
    {
        foreach ($tasks as $key => $task) {
            if ($task['id'] == $taskId) {
                return $key;
            }
        }

        return null; // Task not found
    }

    private function exportCSV()
    {
        $tasks = $this->filterAndSortTasks(request());

        // Generate CSV data
        $csvData = [];
        $csvData[] = ['Title', 'Date Added']; // CSV header row

        foreach ($tasks as $task) {
            $csvData[] = [$task->title, $task->date_added];
        }

        $filename = 'tasks.csv';

        // Generate CSV file response
        $response = response()->stream(
            function () use ($csvData) {
                $handle = fopen('php://output', 'w');
                foreach ($csvData as $row) {
                    fputcsv($handle, $row);
                }
                fclose($handle);
            },
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]
        );

        return $response;
    }

}
