<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    public $timestamps = false;
    protected $fillable = ['id', 'name', 'min_weight', 'max_weight', 'height', 'ruler', 'status', 'owner_id'];
    protected $table = 'categories';
}
