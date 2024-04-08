<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:export-data';
    protected $signature = 'export:users';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Command description';
    protected $description = 'Export users data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filename = "users-" . date('d-m-Y_H-i-s') . ".xlsx";
        Excel::store(new UsersExport, $filename);
        $this->info('Users exported successfully.');
    }
}
