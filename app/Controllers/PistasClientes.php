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
        $data = $model->findAll();
        return $this->respond($data, 200);
    }

    public function show($fechaHora = null)
    {
        $model = new PistasClientesModel();
        $data = $model->where('fechaHora', $fechaHora)->first();

        return ($data)
            ? $this->respond($data)
            : $this->failNotFound('No se encontró la reserva con esa fecha y hora.');
    }

    public function create()
    {
        $model = new PistasClientesModel();
        $json = $this->request->getJSON();

        if ($json) {
            $data = [
                'fechaHora' => $json->fechaHora,
                'correoClientes' => $json->correoClientes,
                'idPistas' => $json->idPistas,
                'numPersonas' => $json->numPersonas,
                'nivelPersonas' => $json->nivelPersonas ?? null,
                'mediaNivel' => $json->mediaNivel ?? null
            ];
        } else {
            $data = [
                'fechaHora' => $this->request->getPost('fechaHora'),
                'correoClientes' => $this->request->getPost('correoClientes'),
                'idPistas' => $this->request->getPost('idPistas'),
                'numPersonas' => $this->request->getPost('numPersonas'),
                'nivelPersonas' => $this->request->getPost('nivelPersonas') ?? null,
                'mediaNivel' => $this->request->getPost('mediaNivel') ?? null
            ];
        }

        if (!$model->insert($data)) {
            return $this->failValidationErrors($model->errors());
        }

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
                'numPersonas' => $json->numPersonas,
                'nivelPersonas' => $json->nivelPersonas ?? null,
                'mediaNivel' => $json->mediaNivel ?? null
            ];
        } else {
            $input = $this->request->getRawInput();
            $data = [
                'fechaHora' => $input['fechaHora'],
                'correoClientes' => $input['correoClientes'],
                'idPistas' => $input['idPistas'],
                'numPersonas' => $input['numPersonas'],
                'nivelPersonas' => $input['nivelPersonas'] ?? null,
                'mediaNivel' => $input['mediaNivel'] ?? null
            ];
        }

        if (!$model->update($fechaHora, $data)) {
            return $this->failValidationErrors($model->errors());
        }

        return $this->respond([
            'status' => 200,
            'error' => null,
            'messages' => [
                'success' => 'Reserva actualizada correctamente'
            ]
        ]);
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