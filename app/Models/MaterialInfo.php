<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialInfo extends Model
{
    protected $table = 'sugestion';
    use HasFactory;

    protected $fillable = ['status'];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
