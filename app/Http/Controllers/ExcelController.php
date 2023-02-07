<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;
use App\Imports\ProductImport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function __construct()
    {
        $periode = Periode::where('active','Y')->get();
        if($periode->count() == 1){
            $periode->first();
        } else {
            return redirect('/periode');
        }
    }

    public function import(Request $request){
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            Excel::import(new ProductImport, $file);
            return redirect('/periode');
        }
        return redirect('/periode');
    }
}
