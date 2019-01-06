<?php


namespace App\Models\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DropleadExport implements FromCollection,WithHeadings
{
    private $droplead;
    private $heading;
    public function __construct($collect,$heading)
    {
        $this->droplead = $collect;
        $this->heading = $heading;
    }
    public function collection()
    {
        
        return $this->droplead;
    }
    public function headings(): array
    {
        return $this->heading;
    }
}