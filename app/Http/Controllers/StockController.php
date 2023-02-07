<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StockController extends Controller
{
    public function index(){
        $now = Periode::where('active','Y')->first();

        $data['parse']   = Transaction::where('type','SO')->where('periode_id', $now->id)->latest()->get();
        $data['periode'] = $now;
        $no = 1;
        return view('stockop.index',compact('data','no'));
    }

    public function create(){
        $now = Periode::where('active','Y')->first();
        $data['periode']    = $now;
        $data['parse']      = Transaction::where('type','SO')->where('periode_id',$now->id)->get();
        $data['warehouse']  = Warehouse::get();
        if($data['parse']->count() == 0){
            $data['product'] = Product::get();
        } else {
            foreach($data['parse'] as $id){
                $ids[] = $id->product_id;
            }
            $data['product'] = Product::whereNotIn('id',$ids)->get();
        }
        return view('stockop.create',compact('data'));
    }

    public function store(Request $request){
        $now = Periode::where('active','Y')->first();
        $item               = new Transaction;
        $item->product_id   = $request->input('product_id');
        $item->date         = Carbon::now()->format('y-m-d');
        $item->price        = 0;
        $item->qty          = $request->input('qty');
        $item->type         = 'SO';
        $item->warehouse_id = $request->input('warehouse_id');
        $item->periode_id   = $now->id;
        $item->save();
        
        return redirect(route('soCreate'));
    }

    public function edit($id){
        $now = Periode::where('active','Y')->first();
        $data['parse']      = Transaction::find($id);
        $data['warehouse']  = Warehouse::get();
        if($data == null){
            return redirect($now);
        }
        return view('stockop.edit',compact('data'));
    }

    public function update(Request $request, $id){
        $x = Transaction::find($id);
        if($x != null){
            $array_update = [           
                'qty'          => $request->input('qty'),
                'warehouse_id' => $request->input('warehouse_id')
            ];                
            $x->update($array_update);
        }
        return redirect()->back();
    }

    public function delete(Request $request){
        $id     = $request->input('id');
        
        $x   = Transaction::find($id);
        if($x != null){
            $x->delete();
        }
        return redirect()->back()->with('status', 'Berhasil Menghapus Gudang');
    }
}
