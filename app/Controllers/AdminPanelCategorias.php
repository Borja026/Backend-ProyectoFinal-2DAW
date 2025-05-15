<?php

namespace App\Controllers;

use App\Models\CategoriasModel;

class AdminPanelCategorias extends BaseController
{
    public function categorias()
    {
        $model = new CategoriasModel();
        $data['categorias'] = $model->findAll();

        $id = $this->request->getGet('editar');
        if ($id) {
            $categoria = $model->find($id);
            if ($categoria) {
                $data['categoriaEditando'] = $categoria;
            }
        }

        return view('admin/categorias_view', $data);
    }


    public function guardarCategoria()
    {
        $model = new CategoriasModel();
        $modo = $this->request->getPost('modo');
        $id = $this->request->getPost('id'); // <- Recuperar el ID correctamente

        $data = [
            'categoria' => $this->request->getPost('categoria'),
            'nombreCarpeta' => $this->request->getPost('nombreCarpeta')
        ];

        if ($modo === 'insertar') {
            // Prevenir nombreCarpeta duplicado
            if ($model->where('nombreCarpeta', $data['nombreCarpeta'])->first()) {
                return redirect()->back()->with('error', 'Ya existe una categoría con esa carpeta.');
            }

            $model->insert($data);

            // Crear carpeta
            $ruta = FCPATH . 'imgs/galeria/' . $data['nombreCarpeta'];
            if (!is_dir($ruta)) {
                mkdir($ruta, 0755, true); // Crea carpeta con permisos y recursivo
            }
        } else {
            $id = $this->request->getPost('id');
            $categoriaAntigua = $model->find($id);

            if ($categoriaAntigua) {
                $nombreAntiguo = $categoriaAntigua['nombreCarpeta'];
                $nombreNuevo = $data['nombreCarpeta'];

                $rutaAntigua = FCPATH . 'imgs/galeria/' . $nombreAntiguo;
                $rutaNueva = FCPATH . 'imgs/galeria/' . $nombreNuevo;

                // Renombrar si cambió el nombre
                if ($nombreAntiguo !== $nombreNuevo && is_dir($rutaAntigua)) {
                    rename($rutaAntigua, $rutaNueva);
                }
            }

            $model->update($id, $data);
        }



        return redirect()->to('/admin/categorias');
    }


    public function eliminarCategoria()
    {
        $id = $this->request->getGet('id');
        $model = new CategoriasModel();
        $categoria = $model->find($id);

        if (!$categoria) {
            return $this->response->setStatusCode(404)->setBody('Categoría no encontrada.');
        }

        // Eliminar carpeta y archivos
        $ruta = FCPATH . 'imgs/galeria/' . $categoria['nombreCarpeta'];
        if (is_dir($ruta)) {
            $this->eliminarCarpetaRecursiva($ruta);
        }

        $model->delete($id, false);

        return redirect()->to('/admin/categorias');
    }

    private function eliminarCarpetaRecursiva($carpeta)
    {
        $archivos = array_diff(scandir($carpeta), ['.', '..']);

        foreach ($archivos as $archivo) {
            $rutaCompleta = $carpeta . DIRECTORY_SEPARATOR . $archivo;
            if (is_dir($rutaCompleta)) {
                $this->eliminarCarpetaRecursiva($rutaCompleta);
            } else {
                unlink($rutaCompleta);
            }
        }

        rmdir($carpeta);
    }
}
