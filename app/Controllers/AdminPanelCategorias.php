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

        $data = [
            'id' => $this->request->getPost('id'),
            'categoria' => $this->request->getPost('categoria'),
            'nombreCarpeta' => $this->request->getPost('nombreCarpeta')
        ];

        if ($modo === 'insertar') {
            $model->insert($data);
        } else {
            $model->update($data['id'], $data);
        }

        return redirect()->to('/admin/categorias');
    }

    public function eliminarCategoria()
    {
        $id = $this->request->getGet('id');

        $model = new CategoriasModel();
        $model->delete($id, false);

        return redirect()->to('/admin/categorias');
    }
}
