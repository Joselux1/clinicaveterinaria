<?php

namespace App\Models;

use CodeIgniter\Model;

class MedicamentoModel extends Model
{
    protected $table = 'medicamento';  // El nombre de la tabla
    protected $primaryKey = 'PK_ID_MEDICAMENTO';  // Clave primaria de la tabla
    protected $allowedFields = ['NOMBRE', 'DESCRIPCION', 'CITA_ID', 'FECHA_BAJA'];  // Campos que se pueden insertar o actualizar
    protected $useTimestamps = false;  // Si no se usan timestamps, ponemos esto en falso

    // Si deseas agregar más métodos como 'findByName'
    public function findByName(string $nombre)
    {
        return $this->where('NOMBRE', $nombre)->first();
    }
}
