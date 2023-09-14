<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Exports\TasksExport;
use App\Imports\TasksImport;
use App\Models\Task;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class importTaskController extends Controller
{
    public function __invoke(Request $request)
    {
        // Validate the file, e.g., check for allowed extensions, etc.
        $file = $request->file('file'); // Get the uploaded file
        Excel::import(new TasksImport(), $file);
        return redirect()->back()->with('success', 'Data imported successfully');
    }

}
