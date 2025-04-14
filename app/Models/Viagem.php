<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Viagem extends Model
{
    use HasFactory;

    protected $table = 'viagem';

    protected $fillable = [
        'user_id',
        'pais_id',
        'data_ida',
        'data_volta',
        'situacao',
        'visto', // ✅ Aqui!
    ];

    // Relação com o usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relação com o país
    public function pais()
    {
        return $this->belongsTo(Pais::class);
    }
}
