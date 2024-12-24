<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $table = "records";
    protected $fillable = [
        'title',
        'address',
        'description',
        'category_id',
        'longitude',
        'latitude'
    ];
    use HasFactory;

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
