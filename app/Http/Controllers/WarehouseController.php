<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index(){
        $data['parse'] = Warehouse::latest()->get();
        $no = 1;
        return view('warehouse.index',compact('data','no'));
    }

    public function create(){
        return view('warehouse.create');
    }

    public function store(Request $request){
        $data           = new Warehouse;
        $data->name     = $request->input('name');
        $data->save();

        return redirect()->back();
    }

    public function edit($id){
        $data   = Warehouse::find($id);
        if($data == null){
            return redirect()->back();
        }
        return view('warehouse.edit',compact('data'));
    }

    public function update(Request $request, $id){
        $x = Warehouse::find($id);
        $array_update = [
            'name'    => $request->input('name'),
        ];                
        $x->update($array_update);
        return redirect()->back();
    }

    public function delete(Request $request){
        $id     = $request->input('id');
        
        $x   = Warehouse::find($id);
        if($x != null){
            $x->delete();
        }
        return redirect()->back()->with('status', 'Berhasil Menghapus Gudang');
    }
}
