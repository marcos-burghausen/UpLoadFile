<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class MoviData extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $collection = 'movimentacoes';

    protected $fillable = [
        'coop',
        'agencia',
        'conta',
        'nome_correntista',
        'cod',
        'descricao',
        'debito',
        'credito',
        'data',
        'hora',
    ];
}
