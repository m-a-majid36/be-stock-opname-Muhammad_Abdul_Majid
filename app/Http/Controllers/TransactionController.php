<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(){
        $now = Periode::where('active','Y')->first();
        $data['parse'] = Transaction::where('periode_id', $now->id)->where('type','!=','SO')->orderBy('date','desc')->get();
        $no = 1;
        return view('transaction.index',compact('data','no'));
    }

    public function create(){
        $now = Periode::where('active','Y')->first();
        $data['product'] = Transaction::where('type','SO')->where('periode_id', $now->id)->get();
        $data['now']     = Carbon::now()->format('Y-m-d');
        return view('transaction.create',compact('data'));
    }

    public function store(Request $request){
        $now = Periode::where('active','Y')->first();
        $key1 = 0;
        $key2 = 0;
        $key3 = 0;
        $key4 = 0;
        if($request->input('type') == 'S'){
            foreach ($request->input('product_id') as $row){
                $wh = Transaction::where('product_id',$request->input('product_id.'.$key1++))->where('periode_id',$now->id)->where('type','SO')->first();
                if($wh == NULL){
                    return redirect()->back()->with('error','Ada Kesalahan !, Barang mungkin belum di Stok Opname untuk periode ini');
                }
                $charges[] = [
                    'date'         => $request->input('date'),
                    'type'         => $request->input('type'),
                    'product_id'   => $request->input('product_id.'.$key2++),
                    'qty'          => $request->input('qty.'.$key3++)*-1,
                    'price'        => $request->input('price.'.$key4++),
                    'warehouse_id' => $wh->warehouse->id,
                    'periode_id'   => $now->id
                ];
            }
        } elseif($request->input('type') == 'B'){
            $now = Periode::where('active','Y')->first();
            foreach ($request->input('product_id') as $row){
                $wh = Transaction::where('product_id',$request->input('product_id.'.$key1++))->where('periode_id',$now->id)->where('type','SO')->first();
                if($wh == NULL){
                    return redirect()->back()->with('error','Ada Kesalahan !, Barang mungkin belum di Stok Opname untuk periode ini');
                }
                $charges[] = [
                    'date'         => $request->input('date'),
                    'type'         => $request->input('type'),
                    'product_id'   => $request->input('product_id.'.$key2++),
                    'qty'          => $request->input('qty.'.$key3++),
                    'price'        => $request->input('price.'.$key4++),
                    'warehouse_id' => $wh->warehouse->id,
                    'periode_id'   => $now->id
                ];
            }
        } else {
            return redirect()->back();
        }
        
        Transaction::insert($charges);
        return redirect()->back();
    }

    public function delete(Request $request){
        $id     = $request->input('id');
        
        $x   = Transaction::find($id);
        if($x != null){
            $x->delete();
        }
        return redirect()->back()->with('status', 'Berhasil Menghapus Product');
    }
}
