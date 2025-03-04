<?php
namespace App\Controllers;

use App\Models\MascotaModel;
use CodeIgniter\Controller;

class MascotaControlador extends BaseController
{
    public function index()
    {
        $mascotaModel = new MascotaModel();
    
        // Capturar filtros de búsqueda
        $nombre = $this->request->getVar('NOMBRE');
        $especie = $this->request->getVar('ESPECIE');
        $raza = $this->request->getVar('RAZA');
        $edad = $this->request->getVar('EDAD');
        $fecha_baja = $this->request->getVar('FECHA_BAJA');
        $filtroFechaBaja = $this->request->getVar('filtro_fecha_baja') ?? '1'; // Activos por defecto
    
        // Capturar parámetros de ordenación
        $ordenar_por = $this->request->getVar('ordenar_por') ?? 'NOMBRE';
        $ordenar_direccion = $this->request->getVar('ordenar_direccion') ?? 'asc';
    
        // Construcción de la consulta
        $query = $mascotaModel->select('mascota.*, cliente.NOMBRE as CLIENTE_NOMBRE')
                              ->join('cliente', 'mascota.CLIENTE_ID = cliente.PK_ID_CLIENTE', 'left');
    
        // Aplicar filtros de búsqueda
        if (!empty($nombre)) {
            $query->like('mascota.NOMBRE', $nombre);
        }
    
        if (!empty($especie)) {
            $query->like('mascota.ESPECIE', $especie);
        }
    
        if (!empty($raza)) {
            $query->like('mascota.RAZA', $raza);
        }
    
        if (!empty($edad)) {
            $query->where('mascota.EDAD', $edad);
        }
    
        // Aplicar filtro según el estado de baja
        switch ($filtroFechaBaja) {
            case '1': // Activos
                $query->where('mascota.FECHA_BAJA', null);
                break;
            case '2': // Archivados
                $query->where('mascota.FECHA_BAJA IS NOT NULL');
                break;
            case '3': // Todos (sin filtro)
                break;
            default:
                $query->where('mascota.FECHA_BAJA', null); // Por defecto, activos
                break;
        }
    
        // Aplicar ordenación
        $columnas_validas = ['NOMBRE', 'ESPECIE', 'RAZA', 'EDAD'];
        if (in_array($ordenar_por, $columnas_validas)) {
            $query->orderBy('mascota.' . $ordenar_por, $ordenar_direccion);
        }
    
        // Paginación
        $perPage = 3; 
        $data['mascotas'] = $query->paginate($perPage);
        $data['pager'] = $mascotaModel->pager;
    
        // Enviar filtros y ordenación a la vista
        $data['nombre'] = $nombre ?? '';
        $data['especie'] = $especie ?? '';
        $data['raza'] = $raza ?? '';
        $data['edad'] = $edad ?? '';
        $data['fecha_baja'] = $fecha_baja ?? '';
        $data['filtro_fecha_baja'] = $filtroFechaBaja;
        $data['ordenar_por'] = $ordenar_por;
        $data['ordenar_direccion'] = $ordenar_direccion;
    
        return view('lista_mascota', $data);
    }
    
    
    public function guardarMascota($id = null)
    {
        $mascotaModel = new MascotaModel();
        helper(['form', 'url']);
        // Cargar datos de la mascota si es edición
        $data['mascota'] = $id ? $mascotaModel->find($id) : null;

        if ($this->request->getMethod() == 'POST') {
            // Reglas de validación
            $validation = \Config\Services::validation();
            $validation->setRules([
                'NOMBRE' => 'required|min_length[3]|max_length[50]',
                'ESPECIE' => 'required|min_length[3]|max_length[50]',
                'RAZA' => 'required|min_length[3]|max_length[50]',
                'EDAD' => 'required|integer',
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                // Mostrar errores de validación
                $data['validation'] = $validation;
            } else {
                // Preparar datos del formulario
                $mascotaData = [
                    'NOMBRE' => $this->request->getPost('NOMBRE'),
                    'ESPECIE' => $this->request->getPost('ESPECIE'),
                    'RAZA' => $this->request->getPost('RAZA'),
                    'EDAD' => $this->request->getPost('EDAD'),
                    'CLIENTE_ID' => $this->request->getPost('CLIENTE_ID'),
                    'FECHA_BAJA' => null  // Si es necesario gestionar fecha baja, se puede manejar aquí
                ];

                if ($id) {
                    // Actualizar mascota existente
                    $mascotaModel->update($id, $mascotaData);
                    $message = 'Mascota actualizada correctamente.';
                } else {
                    // Crear nueva mascota
                    $mascotaModel->save($mascotaData);
                    $message = 'Mascota creada correctamente.';
                }

                // Redirigir al listado con un mensaje de éxito
                return redirect()->to('/mascotas')->with('success', $message);
            }
        }

        // Cargar la vista del formulario (crear/editar)
        return view('mascota_form', $data);
    }

    public function borrar($id)
    {
        $mascotaModel = new MascotaModel();
        $fecha_baja = date('Y-m-d H:i:s'); // Fecha actual
        $mascotaModel->update($id, ['FECHA_BAJA' => $fecha_baja]);
    
        return redirect()->to('/mascotas')->with('success', 'Mascota marcada como dada de baja correctamente.');
    }
    public function reactivar($id)
    {
        $mascotaModel = new MascotaModel();
        $mascotaModel->update($id, ['FECHA_BAJA' => null]);
    
        return redirect()->to('/mascotas')->with('success', 'Mascota reactivado correctamente.');
    }
    public function exportarCSV()
    {
        $mascotaModel = new MascotaModel();
    
        // Obtener filtros desde la URL
        $filtro_nombre = $this->request->getGet('NOMBRE') ?? '';
        $filtro_especie = $this->request->getGet('ESPECIE') ?? '';
        $filtro_raza = $this->request->getGet('RAZA') ?? '';
        $filtro_cliente_id = $this->request->getGet('CLIENTE_ID') ?? '';
        $filtro_fecha_baja = $this->request->getGet('filtro_fecha_baja') ?? '3'; // 1: Activos, 2: Baja, 3: Todos
    
        // Construir la consulta con filtros
        $mascotasQuery = $mascotaModel
            ->select('NOMBRE, ESPECIE, RAZA, EDAD, CLIENTE_ID, FECHA_BAJA');
    
        // Aplicar filtros dinámicamente
        if (!empty($filtro_nombre)) {
            $mascotasQuery->like('NOMBRE', $filtro_nombre);
        }
    
        if (!empty($filtro_especie)) {
            $mascotasQuery->like('ESPECIE', $filtro_especie);
        }
    
        if (!empty($filtro_raza)) {
            $mascotasQuery->like('RAZA', $filtro_raza);
        }
    
        if (!empty($filtro_cliente_id)) {
            $mascotasQuery->where('CLIENTE_ID', $filtro_cliente_id);
        }
    
        if ($filtro_fecha_baja == '1') { // Solo activos
            $mascotasQuery->where('FECHA_BAJA IS NULL');
        } elseif ($filtro_fecha_baja == '2') { // Solo dados de baja
            $mascotasQuery->where('FECHA_BAJA IS NOT NULL');
        }
    
        // Obtener los datos filtrados
        $mascotas = $mascotasQuery->findAll();
    
        // Si no hay datos, evitar exportación vacía
        if (empty($mascotas)) {
            echo "No se encontraron mascotas con los filtros aplicados.";
            exit;
        }
    
        // Definir nombre del archivo
        $filename = 'mascotas_filtradas_' . date('Ymd') . '.csv';
    
        // Encabezados para descarga
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: text/csv; charset=UTF-8");
    
        // Abrir la salida para escribir
        $output = fopen('php://output', 'w');
    
        // Encabezados CSV
        fputcsv($output, ['Nombre', 'Especie', 'Raza', 'Edad', 'ID Cliente', 'Fecha Baja']);
    
        foreach ($mascotas as $mascota) {
            fputcsv($output, [
                $mascota['NOMBRE'],
                $mascota['ESPECIE'],
                $mascota['RAZA'],
                $mascota['EDAD'],
                $mascota['CLIENTE_ID'],
                $mascota['FECHA_BAJA'] ?? 'Activo'
            ]);
        }
    
        fclose($output);
        exit();
    }
    
}
