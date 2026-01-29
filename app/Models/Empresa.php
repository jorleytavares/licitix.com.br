<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'logo_path',
        'email_contato',
        'telefone_contato',
        'endereco',
        'website',
        'plano',
        'status',
    ];

    public function usuarios()
    {
        return $this->hasMany(User::class);
    }
}
