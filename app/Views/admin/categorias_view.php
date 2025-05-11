<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Categorías</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f8f9fa;
        }

        h2 {
            color: #343a40;
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 0 auto;

            & select {
                text-align: center;
                padding: 5px;

                & option {
                    padding: 5px;
                }
            }
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: #495057;
        }

        input[type="text"],
        input[type="number"],
        input[type="email"],
        input[type="date"] {
            width: 95%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        button {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;

            &:hover {
                background-color: #0056b3;
            }
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;

            & th,
            & td {
                padding: 12px;
                text-align: left;
                border-bottom: 1px solid #dee2e6;
                text-align: center;
            }

            & th {
                background-color: #e9ecef;
                color: #495057;
            }

            & tr:hover {
                background-color: #f1f3f5;
            }
        }

        .acciones {
            display: flex;
            justify-content: space-evenly;

            & a {
                display: block;
                width: max-content;
                padding: 8px;
                border-radius: 10px;
                color: white;
                text-decoration: none;

                &:nth-child(1) {
                    background-color: #007bff;
                }

                &:nth-child(2) {
                    background-color: #ff0000;
                }

                &:hover {

                    &:nth-child(1) {
                        background-color: rgb(0, 87, 179);
                    }

                    &:nth-child(2) {
                        background-color: rgb(177, 0, 0);
                    }
                }
            }
        }

        #filtro {
            width: 300px;
            padding: 8px;
            margin-bottom: 20px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        p {
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <?= view('admin/menu') ?>


    <h2>Gestión de Categorías</h2>
    <form action="<?= base_url('admin/categorias/guardar') ?>" method="post">
        <input type="hidden" name="modo" value="<?= isset($categoriaEditando) ? 'editar' : 'insertar' ?>">
        <label>ID:</label>
        <input type="text" name="id" value="<?= esc($categoriaEditando['id'] ?? '') ?>" <?= isset($categoriaEditando) ? 'readonly' : '' ?> required>
        <label>Nombre Categoría:</label>
        <input type="text" name="categoria" value="<?= esc($categoriaEditando['categoria'] ?? '') ?>" required>
        <label>Nombre Carpeta:</label>
        <input type="text" name="nombreCarpeta" value="<?= esc($categoriaEditando['nombreCarpeta'] ?? '') ?>" required>
        <button type="submit"><?= isset($categoriaEditando) ? 'Actualizar' : 'Insertar' ?></button>
    </form>

    <!-- No funciona, hacer que funcione -->
    <p>Buscar categoria: <input type="text" id="filtro" placeholder="Buscar categoria..."></p>

    <table id="tablaCategorias">
        <thead>
            <tr>
                <th>ID</th>
                <th>Categoría</th>
                <th>Carpeta</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categorias as $cat): ?>
                <tr>
                    <td><?= esc($cat['id']) ?></td>
                    <td><?= esc($cat['categoria']) ?></td>
                    <td><?= esc($cat['nombreCarpeta']) ?></td>
                    <td class="acciones">
                        <a href="<?= base_url('admin/categorias?editar=' . $cat['id']) ?>">Editar</a>
                        <a href="<?= base_url('admin/categorias/eliminar?id=' . $cat['id']) ?>"
                            onclick="return confirm('¿Eliminar?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

<!-- Funcionalidad del Filtro -->
<script>
    const filtroInput = document.getElementById('filtro');
    const tabla = document.getElementById('tablaCategorias').getElementsByTagName('tbody')[0];

    filtroInput.addEventListener('keyup', function () {
        const filtro = this.value.toLowerCase();
        const filas = tabla.getElementsByTagName('tr');

        Array.from(filas).forEach(fila => {
            const textoFila = fila.textContent.toLowerCase();
            fila.style.display = textoFila.includes(filtro) ? '' : 'none';
        });
    });
</script>

</html>