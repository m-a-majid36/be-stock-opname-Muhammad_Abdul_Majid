<?php

namespace App\Models;

use App\Models\Measure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function measure()
    {
        return $this->belongsTo(Measure::class);
    } 
}
