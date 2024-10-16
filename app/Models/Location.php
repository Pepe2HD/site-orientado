<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $table = 'locations';

    protected $fillable = [
        'nLocal',
        'latitude',
        'longitude',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    

    public function article()
{
    return $this->belongsTo(Artigo::class, 'article_id');
}

}
