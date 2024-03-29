<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries_names';
    use HasFactory;

    public function user()
    {
        return $this->hasOne(User::class, 'country_id', 'name');
    }
}
