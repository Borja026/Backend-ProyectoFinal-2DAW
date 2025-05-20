<?php

namespace App\Models;

use CodeIgniter\Model;

class PistasClientesModel extends Model
{
    protected $table = 'pistasclientes';
    protected $primaryKey = 'fechaHora';
    protected $allowedFields = ['fechaHora', 'correoClientes', 'idPistas', 'numPersonas', 'nivelPersonas', 'mediaNivel'];

    protected $validationRules = [
        'fechaHora' => 'valid_date',
        'correoClientes' => 'valid_email',
        'idPistas' => 'integer',
        'numPersonas' => 'integer|greater_than[0]',
        'nivelPersonas' => 'string|max_length[255]',
        'mediaNivel' => 'decimal|greater_than_equal_to[0]|less_than_equal_to[10]'
    ];
}
