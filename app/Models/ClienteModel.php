<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table = 'cliente'; // Nombre de la tabla
    protected $primaryKey = 'PK_ID_CLIENTE';  // Clave primaria
    protected $allowedFields = ['NOMBRE', 'CONTRASEÑA', 'CORREO_ELECTRONICO', 'FECHA_BAJA'];


    
    public function findByEmail(string $email)
    {
        return $this->where('CORREO_ELECTRONICO', $email)->first();
    }
}
?>
