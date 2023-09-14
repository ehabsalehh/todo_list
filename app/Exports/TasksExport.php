<?php

namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromCollection;

class TasksExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Task::select('title', 'date_added')->get();
    }

    public function headings(): array
    {
        return [
            'Title',
            'Date Added',
        ];
    }
}
