<?php

namespace App\Models;

use CodeIgniter\Model;

class RolModel extends Model
{
    protected $table = 'rol';  // El nombre de la tabla
    protected $primaryKey = 'PK_ID_ROL';  // Clave primaria de la tabla
    protected $allowedFields = ['PK_ID_ROL','ROL'];  // Campos que se pueden insertar o actualizar
    protected $useTimestamps = false;  

    // Si deseas agregar más métodos como 'findByName'
    public function FindbyRol(string $rol)
    {
        return $this->where('ROL', $rol)->first();
    }
}
