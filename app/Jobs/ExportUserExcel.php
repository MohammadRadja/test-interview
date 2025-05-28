<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Export;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;

class ExportUserExcel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $exportId;

    public function __construct($exportId)
    {
        $this->exportId = $exportId;
    }

    public function handle()
    {
        $export = Export::find($this->exportId);
        $export->update(['status' => 'processing']);

        $filename = 'exports/users_' . time() . '.xlsx';
        Excel::store(new UsersExport, $filename, 'public');

        $export->update([
            'file_path' => $filename,
            'status' => 'done',
        ]);
    }

    public function failed(\Throwable $exception)
    {
        Export::find($this->exportId)?->update(['status' => 'failed']);
    }
}
