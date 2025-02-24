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
            // Retornar errores en formato JSON
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ]);
        }
    
        // Datos del evento
        $eventoData = [
            'TITULO' => $this->request->getPost('TITULO'),
            'FECHA_INICIO' => $this->request->getPost('FECHA_INICIO'),
            'FECHA_FIN' => $this->request->getPost('FECHA_FIN'),
            'DESCRIPCION_ES' => $this->request->getPost('DESCRIPCION_ES'),
            'DESCRIPCION_ENG' => $this->request->getPost('DESCRIPCION_ENG'),
            'FECHA_ELIMINACION' => null
        ];
    
        // Guardar o actualizar
        if ($id) {
            $eventoModel->update($id, $eventoData);
            $message = 'Evento actualizado correctamente.';
        } else {
            $eventoModel->save($eventoData);
            $message = 'Evento creado correctamente.';
        }
    
       return redirect()->to('/eventos')->with('success', $message);
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
            return $this->response->setJSON([]); // No hay eventos
        }
    
        $eventosFormateados = [];
        foreach ($eventos as $evento) {
            $eventosFormateados[] = [
                'id' => $evento['PK_ID_EVENTO'],  
                'title' => $evento['TITULO'],
                'start' => $evento['FECHA_INICIO'],
                'end' => $evento['FECHA_FIN'] ?? $evento['FECHA_INICIO'],
                'description' => $evento['DESCRIPCION_ES'] 
            ];
        }
    
        // Habilitar CORS
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Content-Type')
            ->setJSON($eventosFormateados);
    }
    

}