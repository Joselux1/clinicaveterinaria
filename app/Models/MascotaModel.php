<?php
namespace App\Models;

use CodeIgniter\Model;

class MascotaModel extends Model
{
    protected $table = 'mascota';  // El nombre de la tabla
    protected $primaryKey = 'PK_ID_MASCOTA';  // La clave primaria de la tabla
    protected $allowedFields = ['NOMBRE', 'ESPECIE', 'RAZA', 'EDAD', 'CLIENTE_ID', 'FECHA_BAJA'];  // Los campos que se pueden insertar o actualizar
    protected $useTimestamps = false;

// Método para encontrar mascota por nombre
public function findByName(string $nombre)
{
    return $this->where('NOMBRE', $nombre)->first();  // Retorna la primera mascota con el nombre dado
}


}