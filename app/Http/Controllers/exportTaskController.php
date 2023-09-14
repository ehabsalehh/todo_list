<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Exports\TasksExport;
use App\Imports\TasksImport;
use App\Models\Task;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class exportTaskController extends Controller
{
    public function __invoke(Request $request)
    {
        $fileName = 'tasks.xlsx';
        return Excel::download(new TasksExport(), $fileName);
    }
}
