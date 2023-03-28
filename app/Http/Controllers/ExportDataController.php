<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use DataTables, Auth, DB;
use Validator,Redirect,Response;

use App\Exports\PbjExport;
use App\Exports\PrExport;
use App\Exports\PoExport;
use App\Exports\WoExport;
use App\Exports\CeklistAllExport;
use App\Exports\StockExport;
use App\Exports\BatchStockExport;

class ExportDataController extends Controller
{
    public function exportPBJ(Request $req){
        return Excel::download(new PbjExport($req), 'Laporan-PBJ.xlsx');
    }

    public function exportPR(Request $req){
        return Excel::download(new PrExport($req), 'Laporan-Purchase-Request.xlsx');
    }

    public function exportWO(Request $req){
        return Excel::download(new WoExport($req), 'Laporan-WO.xlsx');
    }

    public function exportPO(Request $req){
        return Excel::download(new PoExport($req), 'Laporan-Purchase-Order.xlsx');
    }

    public function exportCeklistAll(Request $req){
        return Excel::download(new CeklistAllExport($req), 'Laporan-Ceklist.xlsx');
    }

    public function exportStock(Request $req){
        return Excel::download(new StockExport($req), 'Laporan-Stock.xlsx');
    }

    public function exportBatchStock(Request $req){
        return Excel::download(new BatchStockExport($req), 'Laporan-Batch-Stock.xlsx');
    }
}