<?php
namespace App\Models;
use CodeIgniter\Model;
class PistasModel extends Model
{
    protected $table = 'pistas';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'reservada'];
}