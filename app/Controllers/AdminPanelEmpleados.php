<?php

namespace App\Controllers;

use App\Models\EmpleadosModel;

class AdminPanelEmpleados extends BaseController
{
    public function empleados()
    {
        $empleadosModel = new EmpleadosModel();
        $data['empleados'] = $empleadosModel->findAll();

        $dni = $this->request->getGet('editar');
        if ($dni) {
            $empleado = $empleadosModel->find($dni);
            if ($empleado) {
                $data['empleadoEditando'] = $empleado;
            }
        }

        return view('admin/empleados_view', $data);
    }

    public function guardarEmpleado()
    {
        helper(['form', 'filesystem']);

        $model = new EmpleadosModel();
        $modo = $this->request->getPost('modo');

        $foto = $this->request->getFile('foto');
        $nombreArchivo = null;

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $nombreArchivo = $foto->getRandomName(); // Nombre aleatorio
            $rutaDestino = FCPATH . 'imgs/empleados';

            // Crear carpeta si no existe
            if (!is_dir($rutaDestino)) {
                mkdir($rutaDestino, 0777, true);
            }

            $foto->move($rutaDestino, $nombreArchivo);
        }

        $data = [
            'dni' => $this->request->getPost('dni'),
            'nombre' => $this->request->getPost('nombre'),
            'apellidos' => $this->request->getPost('apellidos'),
            'fecha' => $this->request->getPost('fecha'),
            'telefono' => $this->request->getPost('telefono')
        ];

        if ($nombreArchivo) {
            $data['foto'] = $nombreArchivo;
        } elseif ($modo === 'editar') {
            // En modo editar, conservar la foto actual si no se sube una nueva
            $data['foto'] = $model->find($data['dni'])['foto'] ?? null;
        }

        if ($modo === 'insertar') {
            $model->insert($data);
        } else {
            $model->update($data['dni'], $data);
        }

        return redirect()->to('/admin/empleados');
    }


    public function eliminarEmpleado()
    {
        $dni = urldecode($this->request->getGet('dni'));

        $model = new EmpleadosModel();

        // Verifica si el empleado existe
        $empleado = $model->find($dni);
        if (!$empleado) {
            return $this->response->setStatusCode(404)->setBody('Empleado no encontrado.');
        }

        // Elimina usando el segundo parámetro 'false'
        $model->delete($dni, false);

        return redirect()->to('/admin/empleados');
    }


}
