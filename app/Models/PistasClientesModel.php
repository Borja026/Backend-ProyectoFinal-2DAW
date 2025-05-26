<?php

namespace App\Models;

use CodeIgniter\Model;

class PistasClientesModel extends Model
{
    protected $table = 'pistasclientes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'fechaHora',
        'correoClientes',
        'idPistas',
        'numPersonas',
        'nivelPersonas',
        'mediaNivel',
        'estadoPago',
        'pago_id',
        'cancelada',
        'fechaCancelacion'
    ];

    protected $validationRules = [
        'fechaHora' => 'valid_date',
        'correoClientes' => 'valid_email',
        'idPistas' => 'integer',
        'numPersonas' => 'integer|greater_than[0]',
        'nivelPersonas' => 'string|max_length[255]',
        'mediaNivel' => 'decimal|greater_than_equal_to[0]|less_than_equal_to[10]',
        'estadoPago' => 'in_list[pagado,reembolsado,pendiente]',
        'pago_id' => 'permit_empty|string|max_length[100]',
        'cancelada' => 'in_list[0,1]',
        'fechaCancelacion' => 'permit_empty|valid_date'
    ];
}
