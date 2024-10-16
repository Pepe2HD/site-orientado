<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'users_adm';

    protected $fillable = [
        'user_id',
        'status',
        'reason'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
