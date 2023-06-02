<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $table = 'firebase_image_url';
    protected $fillable = [
        'url',  
    ];
    use HasFactory;
}
