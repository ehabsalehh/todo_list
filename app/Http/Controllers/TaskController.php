<?php

namespace App\Http\Controllers;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->taskService->index($request);
        }
        return view('tasks');
    }

    public function store(Request $request)
    {
        return $this->taskService->store($request);
    }

    public function update(Request $request, $taskId)
    {
        return $this->taskService->update($request,$taskId);
    }

    public function destroy($taskId)
    {
        return $this->taskService->destroy($taskId);
    }
}
