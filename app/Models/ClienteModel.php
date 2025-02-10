<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table = 'cliente'; // Nombre de la tabla
    protected $primaryKey = 'PK_ID_CLIENTE'; // Clave primaria
    protected $allowedFields = ['NOMBRE', 'CONTRASEÑA', 'CORREO_ELECTRONICO', 'FECHA_BAJA']; // Campos permitidos

    public function findByEmail(string $email)
    {
        return $this->where('CORREO_ELECTRONICO', $email)->first();
    }

    // Método para obtener clientes con su rol
    public function obtenerClientesConRol()
    {
        return $this->select('cliente.*, rol.ROL')
                    ->join('rol', 'cliente.ID_ROL = rol.PK_ID_ROL', 'left')
                    ->findAll();
    }

    // Método para filtrar por rol
    public function filtrarPorRol($rolId)
    {
        return $this->select('cliente.*, rol.ROL')
                    ->join('rol', 'cliente.ID_ROL = rol.PK_ID_ROL', 'left')
                    ->where('cliente.ID_ROL', $rolId)
                    ->findAll();
    }
}
