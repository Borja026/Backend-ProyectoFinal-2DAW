<?php
namespace App\Models;
use CodeIgniter\Model;
class ClientesModel extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'correo';
    protected $allowedFields = ['correo', 'nombre', 'apellidos', 'fecha', 'foto', 'telefono', 'username', 'password', 'sexo', 'nivel', 'posicion', 'recibeClases'];
}