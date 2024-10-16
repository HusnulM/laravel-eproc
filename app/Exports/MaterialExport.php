<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

class MaterialExport implements FromCollection, WithHeadings, WithMapping
{
    protected $req;

    function __construct($req) {
        $this->req = $req;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = DB::table('v_material')->orderBy('material');

        $query->orderBy('id');
        return $query->get();
    }

    public function map($row): array{
        $fields = [
            $row->material,
            $row->matdesc,
            $row->mattypedesc,
            $row->matunit
        ];

        return $fields;
    }

    public function headings(): array
    {
        return [
                "Material",
                "Description",
                "Category",
                "Unit",
        ];
    }
}
