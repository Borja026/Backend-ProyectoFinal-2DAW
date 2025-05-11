<?php

namespace App\Models;

use CodeIgniter\Model;

class PistasClientesModel extends Model
{
    protected $table = 'pistasClientes';
    protected $primaryKey = 'fechaHora';
    protected $allowedFields = ['fechaHora', 'correoClientes', 'idPistas', 'numPersonas'];

    protected $validationRules = [
        'fechaHora' => 'valid_date',
        'correoClientes' => 'valid_email',
        'idPistas' => 'integer',
        'numPersonas' => 'integer|greater_than[0]'
    ];
}
