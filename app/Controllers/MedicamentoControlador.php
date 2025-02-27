<?php

namespace App\Controllers;

use App\Models\MedicamentoModel;
use CodeIgniter\Controller;

class MedicamentoControlador extends BaseController
{
    public function index()
    {
        $medicamentoModel = new MedicamentoModel();
    
        // Capturar filtros de búsqueda
        $nombre = $this->request->getVar('NOMBRE');
        $descripcion = $this->request->getVar('DESCRIPCION');
        $fecha_baja = $this->request->getVar('FECHA_BAJA');
        $filtroFechaBaja = $this->request->getVar('filtro_fecha_baja') ?? '1'; // Activos por defecto
    
        // Capturar parámetros de ordenación
        $ordenar_por = $this->request->getVar('ordenar_por') ?? 'NOMBRE';
        $ordenar_direccion = $this->request->getVar('ordenar_direccion') ?? 'asc';
    
        // Construcción de la consulta
        $query = $medicamentoModel->select('*');
    
        // Aplicar filtros de búsqueda
        if (!empty($nombre)) {
            $query->like('NOMBRE', $nombre);
        }
    
        if (!empty($descripcion)) {
            $query->like('DESCRIPCION', $descripcion);
        }
    
        // Aplicar filtro según el estado de baja
        switch ($filtroFechaBaja) {
            case '1': // Activos
                $query->where('FECHA_BAJA', null);
                break;
            case '2': // Archivados
                $query->where('FECHA_BAJA IS NOT NULL');
                break;
            case '3': // Todos (sin filtro)
                break;
            default:
                $query->where('FECHA_BAJA', null); // Por defecto, activos
                break;
        }
    
        // Aplicar ordenación
        $columnas_validas = ['NOMBRE', 'DESCRIPCION'];
        if (in_array($ordenar_por, $columnas_validas)) {
            $query->orderBy($ordenar_por, $ordenar_direccion);
        }
    
        // Paginación
        $perPage = 3;
        $data['medicamentos'] = $query->paginate($perPage);
        $data['pager'] = $medicamentoModel->pager;
    
        // Enviar filtros y ordenación a la vista
        $data['nombre'] = $nombre ?? '';
        $data['descripcion'] = $descripcion ?? '';
        $data['fecha_baja'] = $fecha_baja ?? '';
        $data['filtro_fecha_baja'] = $filtroFechaBaja;
        $data['ordenar_por'] = $ordenar_por;
        $data['ordenar_direccion'] = $ordenar_direccion;
    
        return view('lista_medicamento', $data);
    }
     
    public function guardarMedicamento($id = null)
    {
        $medicamentoModel = new MedicamentoModel();
        helper(['form', 'url']);
        // Cargar datos del medicamento si es edición
        $data['medicamento'] = $id ? $medicamentoModel->find($id) : null;

        if ($this->request->getMethod() == 'POST') {
            // Reglas de validación
            $validation = \Config\Services::validation();
            $validation->setRules([
                'NOMBRE' => 'required|min_length[3]|max_length[50]',
                'DESCRIPCION' => 'required|min_length[3]|max_length[255]',
     
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                // Mostrar errores de validación
                $data['validation'] = $validation;
            } else {
                // Preparar datos del formulario
                $medicamentoData = [
                    'NOMBRE' => $this->request->getPost('NOMBRE'),
                    'DESCRIPCION' => $this->request->getPost('DESCRIPCION'),
                    'CITA_ID' => $this->request->getPost('CITA_ID'),
                    'FECHA_BAJA' => null // Se puede manejar posteriormente si se requiere
                ];

                if ($id) {
                    // Actualizar medicamento existente
                    $medicamentoModel->update($id, $medicamentoData);
                    $message = 'Medicamento actualizado correctamente.';
                } else {
                    // Crear nuevo medicamento
                    $medicamentoModel->save($medicamentoData);
                    $message = 'Medicamento creado correctamente.';
                }

                // Redirigir al listado con un mensaje de éxito
                return redirect()->to('/medicamentos')->with('success', $message);
            }
        }

        // Cargar la vista del formulario (crear/editar)
        return view('medicamento_form', $data);
    }

    public function borrar($id)
{
    $medicamentoModel = new MedicamentoModel();
    $fecha_baja = date('Y-m-d H:i:s'); // Fecha actual
    $medicamentoModel->update($id, ['FECHA_BAJA' => $fecha_baja]);

    return redirect()->to('/medicamentos')->with('success', 'Medicamento marcado como dado de baja correctamente.');
}
public function exportarCSV()
    {
        $medicamentoModel = new MedicamentoModel();
        $medicamentos = $medicamentoModel->select('NOMBRE, DESCRIPCION, CITA_ID, FECHA_BAJA')->findAll();
        
        // Definir el nombre del archivo con fecha
        $filename = 'medicamentos_' . date('Ymd') . '.csv';

        // Encabezados para forzar la descarga
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: text/csv; charset=UTF-8");

   
        $output = fopen('php://output', 'w');

        // Encabezados del CSV
        fputcsv($output, ['Nombre', 'Descripción', 'ID Cita', 'Fecha Baja']);

        foreach ($medicamentos as $medicamento) {
            fputcsv($output, [
                $medicamento['NOMBRE'],
                $medicamento['DESCRIPCION'],
                $medicamento['CITA_ID'],
                $medicamento['FECHA_BAJA'] ?? 'Activo' // Si está null, mostrar "Activo"
            ]);
        }

        fclose($output);
        exit();
    }
    public function reactivar($id)
{
    $medicamentoModel = new MedicamentoModel();
    $medicamentoModel->update($id, ['FECHA_BAJA' => null]);

    return redirect()->to('/medicamentos')->with('success', 'Medicamento reactivado correctamente.');
}

}
