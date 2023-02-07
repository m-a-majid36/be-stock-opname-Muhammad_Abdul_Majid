<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Periode;
use App\Models\Warehouse;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class WarnstockController extends Controller
{
    
    public function index(){
        $now = Periode::where('active','Y')->first();
        $data['periode']    = $now;
        $x = Transaction::where('periode_id', $now->id)->where('type','SO')->get();
        $data['parse'] = $x->groupBy('product_id');
        $data['warehouse'] = Warehouse::get();
        $no = 1;
        return view('warnstock.index',compact('data','no'));
    }

    public function sortByWarehouse($id){
        $now = Periode::where('active','Y')->first();
        $data['periode']    = $now;
        $x = Transaction::where('periode_id', $now->id)->where('type','SO')->where('warehouse_id',$id)->get();
        $data['parse'] = $x->groupBy('product_id');
        $data['warehouse'] = Warehouse::get();
        $no = 1;
        return view('warnstock.index',compact('data','no'));
    }

    public function export($id){
        $now = Periode::where('active','Y')->first();
        $type = 'xls';
        $name = 'Report_Stock_-_'.Carbon::now()->format('dmyHis');
        
        if($id == 'all'){
            $check = Transaction::where('periode_id', $now->id)->where('type','SO')->get();
            if($check->count() == 0){
                echo "<script>window.close();</script>";   
            }
        } else {
            $check = Transaction::where('periode_id', $now->id)->where('type','SO')->where('warehouse_id',$id)->get();
            if($check->count() == 0){
                echo "<script>window.close();</script>";   
            }
        }

        foreach($check->groupBy('product_id') as $item){
            $data[] = [
                'code' => $item[0]->product->code,
                'name' => $item[0]->product->name,
                'price' => $item[0]->product->price,
                'warehouse' => $item[0]->warehouse_id == NULL ? '-' : $item[0]->warehouse->name,
                'so' => $item[0]->type_stock('SO'),
                'buy' => $item[0]->type_stock('B'),
                'sold' => $item[0]->type_stock('S')*-1,
                'total_stock' => $item[0]->total_stock()
            ];
        }
        
        return Excel::create($name, function($excel) use ($data) {
			$excel->sheet('products', function($sheet) use ($data){
				$sheet->fromArray($data);
	        });
        })->download($type);
        echo "<script>window.close();</script>";
    }
}
