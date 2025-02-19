<?php
 
namespace App\Models;
 
use CodeIgniter\Model;
 
class EventModel extends Model
{
    protected $table = 'evento';  // El nombre de la tabla
    protected $primaryKey = 'PK_ID_EVENTO';  // La clave primaria de la tabla
    protected $allowedFields = ['TITULO', 'FECBA_INICIO', 'FECHA_FIN', 'DESCRIPCION_ES', 'DESCRIPCION_ENG', 'FECHA_ELIMINACION'];  // Los campos que se pueden insertar o actualizar
    protected $useTimestamps = false;
 
}