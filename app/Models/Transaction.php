<?php

namespace App\Models;

use App\Models\Periode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function warehouse() {
        return $this->belongsTo(Warehouse::class);
    }

    public function total_stock()
    {
        $p = Periode::where('active','Y')->get();
        if($p->count() == 1){
            $pr = $p->first()->id;
        } else {
            $pr = 0;
        }
        
        $data = Transaction::where('periode_id', '=', $pr)->where('product_id',$this->product_id)->first();

        return $data->total_stock;
    } 

    public function type_stock($type)
    {
        $p = Periode::where('active','Y')->get();
        if($p->count() == 1){
            $pr = $p->first()->id;
        } else {
            $pr = 0;
        }

        $data = Transaction::where('periode_id',$pr)->where('product_id',$this->product_id)->where('type',$type)->first();
        
        return $data->total_stock;
    } 
}
