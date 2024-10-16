<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artigo extends Model
{ 
    use HasFactory;
    
    protected $table = 'article';
   
    protected $guarded = [];

    protected $casts = [
        'categories' => 'array'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function locations()
{
    return $this->hasMany(Location::class, 'article_id');
}


    
}
