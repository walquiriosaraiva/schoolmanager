<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BankReturnData extends Model
{
    use HasFactory;

    protected $table = 'bank_return_data';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nosso_numero',
        'valor_principal',
        'data_de_ocorrencia',
        'carteira',
        'nome_do_sacado'
    ];

    protected $dates = ['data_de_ocorrencia'];

    protected $casts = [
        'data_de_ocorrencia' => 'date',
    ];

}
