<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\PistasClientesModel;

class PistasClientes extends ResourceController
{
    use ResponseTrait;

    public function index()
    {
        $model = new PistasClientesModel();
        return $this->respond($model->findAll(), 200);
    }

    public function show($fechaHora = null)
    {
        $model = new PistasClientesModel();
        $data = $model->where('fechaHora', $fechaHora)->first();

        return ($data)
            ? $this->respond($data)
            : $this->failNotFound('No se encontró la reserva con esa fecha.');
    }

    public function create()
    {
        $model = new PistasClientesModel();
        $data = [
            'fechaHora' => $this->request->getPost('fechaHora'),
            'correoClientes' => $this->request->getPost('correoClientes'),
            'idPistas' => $this->request->getPost('idPistas'),
            'numPersonas' => $this->request->getPost('numPersonas')
        ];

        $model->insert($data);
        $response = [
            'status' => 201,
            'error' => null,
            'messages' => [
                'success' => 'Data Saved'
            ]
        ];

        return $this->respondCreated($data, 201);
    }

    public function update($fechaHora = null)
    {
        $model = new PistasClientesModel();
        $json = $this->request->getJSON();

        if ($json) {
            $data = [
                'fechaHora' => $json->fechaHora,
                'correoClientes' => $json->correoClientes,
                'idPistas' => $json->idPistas,
                'numPersonas' => $json->numPersonas
            ];
        } else {
            $input = $this->request->getRawInput();
            $data = [
                'fechaHora' => $input['fechaHora'],
                'correoClientes' => $input['correoClientes'],
                'idPistas' => $input['idPistas'],
                'numPersonas' => $input['numPersonas']
            ];
        }

        // Insertar
        $model->update($fechaHora, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'messages' => [
                'success' => 'Data Updated'
            ]
        ];

        return $this->respond($response);

    }

    public function delete($fechaHora = null)
    {
        $model = new PistasClientesModel();

        if (!$model->where('fechaHora', $fechaHora)->delete()) {
            return $this->failNotFound('No se encontró la reserva.');
        }

        return $this->respondDeleted(['message' => 'Reserva eliminada correctamente.']);
    }
}
