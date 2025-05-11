<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ClientesModel;


class Clientes extends ResourceController
{
    use ResponseTrait;

    public function index()
    {
        $model = new ClientesModel();
        $data = $model->findAll();
        return $this->respond($data, 200);
    }

    public function show($correo = null)
    {
        $model = new ClientesModel();
        $data = $model->getWhere(['correo' => $correo])->getResult();

        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with correo ' . $correo);
        }
    }

    public function create()
    {
        $model = new ClientesModel();
        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'apellidos' => $this->request->getPost('apellidos'),
            'fecha' => $this->request->getPost('fecha'),
            'foto' => $this->request->getPost('foto'),
            'telefono' => $this->request->getPost('telefono'),
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'sexo' => $this->request->getPost('sexo'),
            'nivel' => $this->request->getPost('nivel'),
            'posicion' => $this->request->getPost('posicion'),
            'recibeClases' => $this->request->getPost('recibeClases')
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

    public function update($correo = null)
    {
        $model = new ClientesModel();
        $json = $this->request->getJSON();

        if ($json) {
            $data = [
                'correo' => $json->correo,
                'nombre' => $json->nombre,
                'apellidos' => $json->apellidos,
                'fecha' => $json->fecha,
                'foto' => $json->foto,
                'telefono' => $json->telefono,
                'username' => $json->username,
                'password' => $json->password,
                'sexo' => $json->sexo,
                'nivel' => $json->nivel,
                'posicion' => $json->posicion,
                'recibeClases' => $json->recibeClases
            ];
        } else {
            $input = $this->request->getRawInput();
            $data = [
                'correo' => $input['correo'],
                'nombre' => $input['nombre'],
                'apellidos' => $input['apellidos'],
                'fecha' => $input['fecha'],
                'foto' => $input['foto'],
                'telefono' => $input['telefono'],
                'username' => $input['username'],
                'password' => $input['password'],
                'sexo' => $input['sexo'],
                'nivel' => $input['nivel'],
                'posicion' => $input['posicion'],
                'recibeClases' => $input['recibeClases']
            ];
        }

        // Insertar
        $model->update($correo, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'messages' => [
                'success' => 'Data Updated'
            ]
        ];

        return $this->respond($response);
    }

    public function delete($correo = null)
    {
        $model = new ClientesModel();
        $data = $model->find($correo);

        if ($data) {
            $model->delete($correo);
            $response = [
                'status' => 200,
                'error' => null,
                'messages' => [
                    'success' => 'Data Deleted'
                ]
            ];

            return $this->respondDeleted($response);

        } else {
            return $this->failNotFound('No Data Found with correo ' . $correo);
        }
    }
}