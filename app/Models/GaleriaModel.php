<?php
namespace App\Models;
use CodeIgniter\Model;
class GaleriaModel extends Model
{
    protected $table = 'galeria';
    protected $primaryKey = 'nombre';
    protected $allowedFields = ['nombre', 'idCategoria'];
}