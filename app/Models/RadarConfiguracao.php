<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RadarConfiguracao extends Model
{
    protected $fillable = [
        'user_id',
        'termos_busca',
        'estados',
        'cidades',
    ];

    protected $casts = [
        'termos_busca' => 'array',
        'estados' => 'array',
        'cidades' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
