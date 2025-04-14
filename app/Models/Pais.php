<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    use HasFactory;

    protected $table = 'pais'; // Define explicitamente o nome da tabela

    protected $fillable = [
        'nome',
    ];
}
