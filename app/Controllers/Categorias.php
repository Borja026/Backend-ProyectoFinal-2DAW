<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\CategoriasModel;


class Categorias extends ResourceController
{
    use ResponseTrait;

    public function index()
    {
        $model = new CategoriasModel();
        $data = $model->findAll();
        return $this->respond($data, 200);
    }

    public function show($id = null)
    {
        $model = new CategoriasModel();
        $data = $model->getWhere(['id' => $id])->getResult();

        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id ' . $id);
        }
    }

    public function create()
    {
        $model = new CategoriasModel();
        $data = [
            'id' => $this->request->getPost('id'),
            'categoria' => $this->request->getPost('categoria'),
            'nombreCarpeta' => $this->request->getPost('nombreCarpeta')
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

    public function update($id = null)
    {
        $model = new CategoriasModel();
        $json = $this->request->getJSON();

        if ($json) {
            $data = [
                'id' => $json->id,
                'categoria' => $json->categoria,
                'nombreCarpeta' => $json->nombreCarpeta
            ];
        } else {
            $input = $this->request->getRawInput();
            $data = [
                'id' => $input['id'],
                'categoria' => $input['categoria'],
                'nombreCarpeta' => $input['nombreCarpeta']
            ];
        }

        // Insertar
        $model->update($id, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'messages' => [
                'success' => 'Data Updated'
            ]
        ];

        return $this->respond($response);
    }

    public function delete($id = null)
    {
        $model = new CategoriasModel();
        $data = $model->find($id);

        if ($data) {
            $model->delete($id);
            $response = [
                'status' => 200,
                'error' => null,
                'messages' => [
                    'success' => 'Data Deleted'
                ]
            ];

            return $this->respondDeleted($response);

        } else {
            return $this->failNotFound('No Data Found with id ' . $id);
        }
    }
}