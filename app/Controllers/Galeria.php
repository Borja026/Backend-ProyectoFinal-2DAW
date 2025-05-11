<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\GaleriaModel;


class Galeria extends ResourceController
{
    use ResponseTrait;

    public function index()
    {
        $model = new GaleriaModel();
        $data = $model->findAll();
        return $this->respond($data, 200);
    }

    public function show($nombre = null)
    {
        $model = new GaleriaModel();
        $data = $model->getWhere(['nombre' => $nombre])->getResult();

        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with nombre ' . $nombre);
        }
    }

    public function create()
    {
        $model = new GaleriaModel();
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'idCategoria' => $this->request->getPost('idCategoria')
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

    public function update($nombre = null)
    {
        $model = new GaleriaModel();
        $json = $this->request->getJSON();

        if ($json) {
            $data = [
                'nombre' => $json->nombre,
                'idCategoria' => $json->idCategoria
            ];
        } else {
            $input = $this->request->getRawInput();
            $data = [
                'nombre' => $input['nombre'],
                'idCategoria' => $input['idCategoria']
            ];
        }

        // Insertar
        $model->update($nombre, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'messages' => [
                'success' => 'Data Updated'
            ]
        ];

        return $this->respond($response);
    }

    public function delete($nombre = null)
    {
        $model = new GaleriaModel();
        $data = $model->find($nombre);

        if ($data) {
            $model->delete($nombre);
            $response = [
                'status' => 200,
                'error' => null,
                'messages' => [
                    'success' => 'Data Deleted'
                ]
            ];

            return $this->respondDeleted($response);

        } else {
            return $this->failNotFound('No Data Found with nombre ' . $nombre);
        }
    }
}