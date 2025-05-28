<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\PistasModel;


class Pistas extends ResourceController
{
    use ResponseTrait;

    public function index()
    {
        $model = new PistasModel();
        $data = $model->findAll();
        return $this->respond($data, 200);
    }

    public function show($id = null)
    {
        $model = new PistasModel();
        $data = $model->getWhere(['id' => $id])->getResult();

        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id ' . $id);
        }
    }

    public function create()
    {
        $model = new PistasModel();
        $data = [
            'id' => $this->request->getPost('id'),
            'reservada' => $this->request->getPost('reservada')
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
        $model = new PistasModel();
        $json = $this->request->getJSON();

        if ($json) {
            $data = [
                'id' => $json->id,
                'reservada' => $json->reservada
            ];
        } else {
            $input = $this->request->getRawInput();
            $data = [
                'id' => $input['id'],
                'reservada' => $input['reservada']
            ];
        }

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
        $model = new PistasModel();
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