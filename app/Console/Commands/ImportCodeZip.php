<?php

namespace App\Console\Commands;

use App\Imports\ZipCodeImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;


class ImportCodeZip extends Command
{
    static private $file_data = 'CPdescarga.xls';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'code_zip:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from xls file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        try {
            $this->line("------------Import data from: ".self::$file_data."------------");

            $file = resource_path('/data/'.self::$file_data);

            if( !file_exists($file) )
            {
                $this->line("the file CPdescarga.xls does not exits");
                return false;
            }

            (new ZipCodeImport)->import($file);

            $this->line("------------Import data from: ".self::$file_data." finish ------------");
        } catch (\Exception $e) {

            $this->line("------------Problem to Import data from: ".self::$file_data."------------");
            $this->line(print_r($e, true));
            return false;
        }

    }
}
