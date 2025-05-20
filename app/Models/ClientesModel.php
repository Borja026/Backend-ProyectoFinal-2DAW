<?php

// namespace App\Models;

// use CodeIgniter\Model;

// class ClientesModel extends Model
// {
//     protected $table = 'clientes';
//     protected $primaryKey = 'correo';
//     protected $allowedFields = ['correo', 'nombre', 'apellidos', 'fecha', 'foto', 'telefono', 'username', 'password', 'sexo', 'nivel', 'posicion', 'recibeClases'];
// }




namespace App\Models;

use CodeIgniter\Model;

class ClientesModel extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'correo';
    protected $allowedFields = [
        'correo',
        'nombre',
        'apellidos',
        'fecha',
        'foto',
        'telefono',
        'username',
        'password',
        'sexo',
        'nivel',
        'posicion',
        'recibeClases'
    ];

    protected $validationRules = [
        'correo' => 'required|valid_email|is_unique[clientes.correo]',
        'nombre' => 'required|min_length[2]',
        'apellidos' => 'required|min_length[2]',
        'fecha' => 'required|valid_date',
        'telefono' => 'required|numeric|min_length[9]',
        'username' => 'required|min_length[3]|is_unique[clientes.username]',
        'password' => 'required|min_length[6]',
        'sexo' => 'permit_empty|in_list[0,1]', // <- permite vacío
        'nivel' => 'required|decimal',
        'posicion' => 'required|in_list[Drive,Revés,Indiferente]',
        'recibeClases' => 'permit_empty|in_list[0,1]', // <- permite vacío
    ];
}