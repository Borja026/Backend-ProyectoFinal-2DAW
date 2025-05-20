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

    public function show($id = null)
    {
        $correo = urldecode($id);
        $model = new ClientesModel();
        $data = $model->getWhere(['correo' => $correo])->getResult();

        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with correo ' . $correo);
        }
    }

    // public function create()
    // {
    //     $model = new ClientesModel();
    //     $json = $this->request->getJSON(true); // Devuelve array asociativo

    //     if (!$json) {
    //         return $this->fail('No se recibió JSON válido');
    //     }

    //     log_message('debug', 'Datos recibidos para insertar: ' . json_encode($json)); // DEBUG

    //     if (!$model->insert($json)) {
    //         log_message('error', 'Error al registrar cliente: ' . json_encode($model->errors()));

    //         // Esta es la forma correcta de devolver errores en CI4 y que Angular los entienda
    //         return $this->respond([
    //             'status' => false,
    //             'errors' => $model->errors()
    //         ], 400);
    //     }

    //     return $this->respond([
    //         'status' => true,
    //         'message' => 'Cliente registrado correctamente.'
    //     ], 201);
    // }
    // public function create()
    // {
    //     $model = new ClientesModel();
    //     $json = $this->request->getJSON(true);

    //     if (!$json) {
    //         return $this->fail('No se recibió JSON válido');
    //     }

    //     log_message('debug', 'Datos recibidos para insertar: ' . json_encode($json)); // DEBUG

    //     // ✅ Mostrar los datos como respuesta (prueba)
    //     return $this->respond($json);  // <- AÑADE ESTO TEMPORALMENTE Y COMENTA LO DEMÁS
    // }
    public function create()
    {
        $model = new ClientesModel();
        $json = $this->request->getJSON(true); // Devuelve array asociativo

        if (!$json) {
            return $this->fail('No se recibió JSON válido');
        }

        log_message('debug', 'Datos recibidos para insertar: ' . json_encode($json)); // DEBUG

        if (!$model->insert($json)) {
            log_message('error', 'Error al registrar cliente: ' . json_encode($model->errors()));

            return $this->respond([
                'status' => false,
                'errors' => $model->errors()
            ], 400);
        }

        return $this->respond([
            'status' => true,
            'message' => 'Cliente registrado correctamente.'
        ], 201);
    }



    public function update($id = null)
    {
        $correo = urldecode($id);
        $model = new ClientesModel();
        $json = $this->request->getJSON();

        if ($json) {
            // OJO: NO incluyas el campo 'correo' aquí
            $data = [
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

        $model->update($correo, $data);

        return $this->respond([
            'status' => 200,
            'error' => null,
            'messages' => ['success' => 'Datos actualizados']
        ]);
    }


    public function delete($id = null)
    {
        $correo = urldecode($id);
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

    public function subirImagenCliente()
    {
        $request = \Config\Services::request();
        $imagen = $this->request->getFile('imagen');
        $correo = $this->request->getPost('correo');
        $anterior = $this->request->getPost('anterior');

        if ($imagen && $imagen->isValid() && !$imagen->hasMoved()) {
            $nuevoNombre = $imagen->getRandomName();
            $imagen->move('imgs/clientes', $nuevoNombre);

            // Borrar imagen anterior si no es la default
            if ($anterior && $anterior !== 'default_user.png' && file_exists('imgs/clientes/' . $anterior)) {
                unlink('imgs/clientes/' . $anterior);
            }

            // Actualizar en la base de datos
            $model = new \App\Models\ClientesModel();
            $model->update($correo, ['foto' => $nuevoNombre]);

            return $this->respond(['nuevaImagen' => $nuevoNombre]);
        }

        return $this->fail('No se pudo subir la imagen.');
    }

}