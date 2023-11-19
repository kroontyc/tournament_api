<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categorie extends Model
{
     protected $fillable = ['id', 'name', 'min_weight', 'max_weight', 'height', 'status', 'owner_id'];
    protected $table = 'categories';
}
