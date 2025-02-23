<?php

namespace App\Controllers;

use App\Models\EventoModel;
use CodeIgniter\Controller;

class EventoControlador extends BaseController
{
    protected $eventoModel;

    public function __construct()
    {
        $this->eventoModel = new EventoModel();
    }

    public function index()
    {
        return view('eventos');
    }

    public function busquedaEventos()
    {
        $query = $this->eventoModel
            ->select('evento.PK_ID_EVENTO, evento.TITULO, evento.FECHA_INICIO, evento.FECHA_FIN, evento.DESCRIPCION_ES, evento.DESCRIPCION_ENG, evento.FECHA_ELIMINACION, tipo_evento.TITULO AS TIPO_EVENTO')
            ->join('tipo_evento', 'evento.ID_TIPO_EVENTO = tipo_evento.PK_ID_TIPO_EVENTO', 'left');

        $nombre = $this->request->getVar('nombre');
        $fecha = $this->request->getVar('fecha');

        if (!empty($nombre)) {
            $query->like('evento.TITULO', $nombre);
        }
        if (!empty($fecha)) {
            $query->where('evento.FECHA_INICIO', $fecha);
        }

        $data = $query->findAll();
        return $this->response->setJSON($data);
    }

    public function guardarEvento($id = null)
    {
        $eventoModel = new EventoModel();
        helper(['form', 'url']);
    
        // Cargar datos del evento si es edición
        $data['evento'] = $id ? $eventoModel->find($id) : null;
    
        if ($this->request->getMethod() == 'POST') {
            // Reglas de validación
            $validation = \Config\Services::validation();
            $validation->setRules([
                'TITULO' => 'required|min_length[3]|max_length[100]',
                'FECHA_INICIO' => 'required',
                'FECHA_FIN' => 'permit_empty',
                'DESCRIPCION_ES' => 'permit_empty|max_length[500]',
                'DESCRIPCION_ENG' => 'permit_empty|max_length[500]'
            ]);
    
            if (!$validation->withRequest($this->request)->run()) {
                // Mostrar errores de validación
                $data['validation'] = $validation;
            } else {
                // Preparar datos del formulario
                $eventoData = [
                    'TITULO' => $this->request->getPost('TITULO'),
                    'FECHA_INICIO' => $this->request->getPost('FECHA_INICIO'),
                    'FECHA_FIN' => $this->request->getPost('FECHA_FIN'),
                    'DESCRIPCION_ES' => $this->request->getPost('DESCRIPCION_ES'),
                    'DESCRIPCION_ENG' => $this->request->getPost('DESCRIPCION_ENG'),
                    'FECHA_ELIMINACION' => null // Se puede manejar posteriormente si es necesario
                ];
    
                if ($id) {
                    // Actualizar evento existente
                    $eventoModel->update($id, $eventoData);
                    $message = 'Evento actualizado correctamente.';
                } else {
                    // Crear nuevo evento
                    $eventoModel->save($eventoData);
                    $message = 'Evento creado correctamente.';
                }
    
                // Redirigir al listado con un mensaje de éxito
                return redirect()->to('/eventos')->with('success', $message);
            }
        }
    
        // Cargar la vista del formulario (crear/editar)
        return view('eventos_form', $data);
    }
    

    public function borrarEventos($id)
    {
        $this->eventoModel->delete($id);
        return redirect()->to('/eventos')->with('success', 'Evento eliminado con éxito');
    }

    public function actualizar($id)
    {
        $data = [
            'TITULO' => $this->request->getPost('TITULO'),
            'FECHA_INICIO' => $this->request->getPost('FECHA_INICIO'),
            'FECHA_FIN' => $this->request->getPost('FECHA_FIN'),
            'DESCRIPCION_ES' => $this->request->getPost('DESCRIPCION_ES'),
            'DESCRIPCION_ENG' => $this->request->getPost('DESCRIPCION_ENG'),
        ];

        $this->eventoModel->update($id, $data);

        return redirect()->to('/eventos')->with('success', 'Evento actualizado con éxito');
    }
    public function obtenerEventos()
{
    $eventoModel = new EventoModel();
    $eventos = $eventoModel->findAll();

    if (empty($eventos)) {
        return $this->response->setJSON([]);
    }
    // Formatear eventos para FullCalendar
    $eventosFormateados = [];
    foreach ($eventos as $evento) {
        $eventosFormateados[] = [
            'id' => $evento['PK_ID_EVENTO'],  // Asegúrate de que este campo sea el ID del evento en la BD
            'title' => $evento['TITULO'],
            'start' => $evento['FECHA_INICIO'],
            'end' => $evento['FECHA_FIN'] ?? $evento['FECHA_INICIO'], // Si no hay fecha fin, usa la de inicio
            'description' => $evento['DESCRIPCION_ES'] // Opcional
        ];
    }

    return $this->response->setJSON($eventosFormateados);
}

}

