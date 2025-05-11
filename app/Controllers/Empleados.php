<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\EmpleadosModel;


class Empleados extends ResourceController
{
    use ResponseTrait;

    public function index()
    {
        $model = new EmpleadosModel();
        $data = $model->findAll();
        return $this->respond($data, 200);
    }

    public function show($dni = null)
    {
        $model = new EmpleadosModel();
        $data = $model->getWhere(['dni' => $dni])->getResult();

        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with DNI ' . $dni);
        }
    }

    public function create()
    {
        $model = new EmpleadosModel();
        $data = [
            'dni' => $this->request->getPost('dni'),
            'nombre' => $this->request->getPost('nombre'),
            'apellidos' => $this->request->getPost('apellidos'),
            'foto' => $this->request->getPost('foto'),
            'fecha' => $this->request->getPost('fecha'),
            'telefono' => $this->request->getPost('telefono')
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

    public function update($dni = null)
    {
        $model = new EmpleadosModel();
        $json = $this->request->getJSON();

        if ($json) {
            $data = [
                'dni' => $json->dni,
                'nombre' => $json->nombre,
                'apellidos' => $json->apellidos,
                'foto' => $json->foto,
                'fecha' => $json->fecha,
                'telefono' => $json->telefono
            ];
        } else {
            $input = $this->request->getRawInput();
            $data = [
                'dni' => $input['dni'],
                'nombre' => $input['nombre'],
                'apellidos' => $input['apellidos'],
                'foto' => $input['foto'],
                'fecha' => $input['fecha'],
                'telefono' => $input['telefono']
            ];
        }

        // Insertar
        $model->update($dni, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'messages' => [
                'success' => 'Data Updated'
            ]
        ];

        return $this->respond($response);
    }

    public function delete($dni = null)
    {
        $model = new EmpleadosModel();
        $data = $model->find($dni);

        if ($data) {
            $model->delete($dni);
            $response = [
                'status' => 200,
                'error' => null,
                'messages' => [
                    'success' => 'Data Deleted'
                ]
            ];

            return $this->respondDeleted($response);

        } else {
            return $this->failNotFound('No Data Found with dni ' . $dni);
        }
    }
}