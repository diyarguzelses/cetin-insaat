<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardOfDirector extends Model
{
    use HasFactory;

    protected $table = 'table_board_of_directors';

    protected $fillable = ['name', 'biography', 'image', 'order'];
}
