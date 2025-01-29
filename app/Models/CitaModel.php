<?php

namespace App\Models;

use CodeIgniter\Model;

class CitaModel extends Model
{
    protected $table = 'cita';  // Nombre de la tabla
    protected $primaryKey = 'PK_ID_CITA';  // Clave primaria
    protected $allowedFields = ['FECHA','DIAGNOSTICO','TRATAMIENTO','VETERINARIO_ID','MASCOTA_ID','FECHA_BAJA'];

    // Función para obtener todas las citas con sus detalles de veterinario y mascota, si es necesario
    public function getCitasConDetalles()
    {
        return $this->select('cita.*, veterinario.NOMBRE AS VETERINARIO_NOMBRE, mascota.NOMBRE AS MASCOTA_NOMBRE')
                    ->join('veterinario', 'cita.VETERINARIO_ID = veterinario.PK_ID_VETERINARIO')
                    ->join('mascota', 'cita.MASCOTA_ID = mascota.PK_ID_MASCOTA')
                    ->findAll();
    }

    // Función para obtener una cita por ID
    public function getCitaPorId($id)
    {
        return $this->where('PK_ID_CITA', $id)->first();
    }

    // Función para marcar una cita como "baja" (en este caso actualizando FECHA_BAJA)
    public function marcarBaja($id)
    {
        return $this->update($id, ['FECHA_BAJA' => date('Y-m-d H:i:s')]);
    }
}
