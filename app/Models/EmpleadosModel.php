<?php
namespace App\Models;
use CodeIgniter\Model;
class EmpleadosModel extends Model
{
    protected $table = 'empleados';
    protected $primaryKey = 'dni';
    protected $allowedFields = ['dni', 'nombre', 'apellidos', 'foto', 'fecha', 'telefono', 'correo', 'password'];
}