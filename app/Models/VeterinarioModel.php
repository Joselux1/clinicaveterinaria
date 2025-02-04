<?php

namespace App\Models;

use CodeIgniter\Model;

class VeterinarioModel extends Model
{
    protected $table = 'veterinario'; // Nombre de la tabla
    protected $primaryKey = 'PK_ID_VETERINARIO';  // Clave primaria
    protected $allowedFields = ['NOMBRE', 'CONTRASEÑA', 'ESPECIALIDAD', 'CORREO_ELECTRONICO', 'FECHA_BAJA'];

    public function findByEmail(string $email)
    {
        return $this->where('CORREO_ELECTRONICO', $email)->first();
    }
}
?>
