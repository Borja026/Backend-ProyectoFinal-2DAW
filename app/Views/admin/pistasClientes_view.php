<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Reservas de Pistas</title>

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

    <h2>Gestión de Reservas de Pistas</h2>

    <form action="<?= base_url('admin/pistasClientes/guardar') ?>" method="post">
        <input type="hidden" name="modo" value="<?= isset($reservaEditando) ? 'editar' : 'insertar' ?>">
        <?php if (isset($reservaEditando)): ?>
            <input type="hidden" name="idPistasOriginal" value="<?= esc($reservaEditando['idPistas']) ?>">
            <input type="hidden" name="fechaHoraOriginal" value="<?= esc($reservaEditando['fechaHora']) ?>">
        <?php endif; ?>

        <label>ID Pista:</label>
        <input type="text" name="idPistas" value="<?= esc($reservaEditando['idPistas'] ?? '') ?>" required>

        <label>Correo Cliente:</label>
        <input type="email" name="correoClientes" value="<?= esc($reservaEditando['correoClientes'] ?? '') ?>" required>

        <label>Fecha y Hora:</label>
        <input type="datetime-local" name="fechaHora"
            value="<?= isset($reservaEditando['fechaHora']) ? date('Y-m-d\TH:i', strtotime($reservaEditando['fechaHora'])) : '' ?>"
            required>

        <label>Número de Personas:</label>
        <input type="number" name="numPersonas" value="<?= esc($reservaEditando['numPersonas'] ?? '') ?>" required>

        <button type="submit"><?= isset($reservaEditando) ? 'Actualizar' : 'Insertar' ?></button>
    </form>

    <!-- No funciona, hacer que funcione -->
    <p>Buscar pista: <input type="text" id="filtro" placeholder="Buscar pista..."></p>

    <table id="tablaPistasClientes">
        <thead>
            <tr>
                <th>ID Pista</th>
                <th>Correo Cliente</th>
                <th>Fecha y Hora</th>
                <th>Nº Personas</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservas as $reserva): ?>
                <tr>
                    <td><?= esc($reserva['idPistas']) ?></td>
                    <td><?= esc($reserva['correoClientes']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($reserva['fechaHora'])) ?></td>
                    <td><?= esc($reserva['numPersonas']) ?></td>
                    <td class="acciones">
                        <a
                            href="<?= base_url('admin/pistasClientes?editar=' . urlencode($reserva['fechaHora']) . '&id=' . $reserva['idPistas']) ?>">Editar</a>
                        <a href="<?= base_url('admin/pistasClientes/eliminar?fechaHora=' . urlencode($reserva['fechaHora']) . '&id=' . $reserva['idPistas']) ?>"
                            onclick="return confirm('¿Seguro que deseas eliminar esta reserva?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

<!-- Funcionalidad del Filtro -->
<script>
    const filtroInput = document.getElementById('filtro');
    const tabla = document.getElementById('tablaPistasClientes').getElementsByTagName('tbody')[0];

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