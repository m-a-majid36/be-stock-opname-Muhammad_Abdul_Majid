<?php

namespace App\Http\Controllers;

use App\Models\Measure;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use \Carbon\Carbon;

class ProductController extends Controller
{
    public function index(){
        $data['parse'] = Product::orderBy('name')->get();
        $no = 1;
        return view('product.index',compact('data','no'));
    }

    public function create(){
        $data['measure'] = Measure::all();
        return view('product.create',compact('data'));
    }

    public function store(Request $request){
        $data               = new Product;
        $data->code         = $request->input('code');
        $data->name         = $request->input('name');
        $data->measure_id   = $request->input('measure_id');
        $data->price        = $request->input('price');
        $data->warn_stock   = $request->input('warn_stock');
        $data->save();

        return redirect(route('pdCreate'));
    }

    public function edit($id){
        $data['measure'] = Measure::all();
        $data['parse']   = Product::find($id);
        if($data == null){
            return redirect()->back();
        }
        return view('product.edit',compact('data'));
    }

    public function update(Request $request, $id){
        $x = Product::find($id);
        if($x != null){
            $array_update = [
                'name'         => $request->input('name'),
                'code'         => $request->input('code'),
                'name'         => $request->input('name'),
                'measure_id'   => $request->input('measure_id'),
                'price'        => $request->input('price'),
                'warn_stock'   => $request->input('warn_stock')
            ];                
            $x->update($array_update);
        }
        return redirect()->back();
    }

    public function delete(Request $request){
        $id     = $request->input('id');
        
        $x   = Product::find($id);
        if($x != null){
            $x->delete();
        }
        return redirect()->back()->with('status', 'Berhasil Menghapus Produk');
    }

    public function deleteAll(){
        Product::deleteAll();
        return redirect()->back()->with('status', 'Berhasil Menghapus Semua Produk');
    }

    public function import(Request $request){
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $ext  = $file->getClientOriginalExtension();
            
            if($ext != 'xls'){
                return redirect()->back()->with('errors', 'Kesalahan: File yang diunggah wajib file excel');
            }
            
            $data = Excel::load($file);
            $insert = array();
            foreach ($data->get() as $element) {
                $data = array(
                    'code' => $element['code'] == null ? '-':$element['code'],
                    'name' => $element['name'] == null ? '-':$element['name'],
                    'warn_stock' => $element['warn_stock'] == null ? 0:$element['warn_stock'],
                    'price' => $element['price'] == null ? 0:$element['price'],
                    'measure_id' => 7
                );
                $insert[] = $data;
            }
            //dd($insert);
            try {
                Product::insert($insert);    
            } catch (Exception $e) {
                $errorCode = $e->errorInfo[1];
                if($errorCode == 1062){
                    return redirect()->back()->with('errors', 'Kesalahan: 1062 - Terindikasi ada duplikasi kode barang. Pastikan tidak ada duplikasi Kode Barang sebelum Anda mengunggah berkas Anda');
                }
                return redirect()->back()->with('errors', 'Kesalahan: 0 - Template yang Anda unggah mungkin tidak sesuai ketentuan, Export data terlebih dahulu untuk mengetahui template');
            }
            return redirect()->back()->with('status','Sukses diimpor');
        }
        return redirect()->back();
    }

    public function export(){
        $type = 'xls';
        $name = 'Backup_Products_-_'.Carbon::now()->format('dmyHis');
        $data = Product::get()->toArray();

        return Excel::create($name, function($excel) use ($data) {
			$excel->sheet('products', function($sheet) use ($data){
				$sheet->fromArray($data);
	        });
        })->download($type);
        echo "<script>window.close();</script>";
    }
}
