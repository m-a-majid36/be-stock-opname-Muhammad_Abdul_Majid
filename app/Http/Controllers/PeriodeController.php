<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    public function index(){
        $data['parse'] = Periode::latest()->get();
        $no = 1;
        return view('periode.index',compact('data','no'));
    }

    public function create(){
        return view('periode.create');
    }

    public function store(Request $request){
        $array_update = [
            'active'    => 'N',
        ];
        $x = Periode::where('active','Y');
        if($x->count() > 0){
            $x->update($array_update);
        }

        $data           = new Periode;
        $data->name     = $request->input('name');
        $data->active   = 'Y';
        $data->save();

        return redirect()->back();
    }

    public function edit($id){
        $data   = Periode::find($id);
        if($data == null){
            return redirect()->back();
        }
        return view('periode.edit',compact('data'));
    }

    public function update(Request $request, $id){
        $x = Periode::find($id);
        if($request->input('active') == $x->active){
            $array_update = [
                'name'    => $request->input('name'),
            ];
            if($x->count() > 0){
                $x->update($array_update);
            } else {
                return redirect()->back();
            }
        } else {
            $array_update = [
                'name'    => $request->input('name'),
                'active'    => $request->input('active')
            ];
            $up = Periode::where('active','Y');
            if($request->input('active') == 'Y'){
                $up->update(['active' => 'N']);
                $x->update($array_update);
            } else {
                $x->update($array_update);
            }
        }
        return redirect()->back();
    }

    public function delete(Request $request){
        $id     = $request->input('id');
        
        $user   = Periode::find($id);
        if($user != null){
            $user->delete();
        }
        return redirect()->back()->with('status', 'Berhasil Menghapus Periode');
    }
}
