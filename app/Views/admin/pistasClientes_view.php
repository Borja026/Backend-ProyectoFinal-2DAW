<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Reservas de Pistas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 40px 20px;
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
            max-width: 650px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            column-gap: 20px;
            row-gap: 15px;
        }

        form label {
            font-weight: bold;
        }

        form input,
        form select {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            width: 100%;
            box-sizing: border-box;
        }

        form button {
            grid-column: 1 / -1;
            margin-top: 10px;
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        table th,
        table td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f2f2f2;
        }

        .acciones a {
            margin: 0 5px;
            text-decoration: none;
            color: white;
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 14px;
        }

        .acciones a:first-child {
            background-color: #28a745;
        }

        .acciones a:last-child {
            background-color: #dc3545;
        }

        .acciones a:hover {
            opacity: 0.8;
        }

        #filtro {
            width: 300px;
            padding: 8px;
            margin: 20px auto;
            display: block;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <?= view('admin/menu') ?>

    <h2>Gestión de Reservas de Pistas</h2>

    <form action="<?= base_url('admin/pistasClientes/guardar') ?>" method="post">
        <input type="hidden" name="modo" value="<?= isset($reservaEditando) ? 'editar' : 'insertar' ?>">
        <?php if (isset($reservaEditando)): ?>
            <input type="hidden" name="idReserva" value="<?= esc($reservaEditando['id']) ?>">
        <?php endif; ?>

        <label>ID Pista:</label>
        <select name="idPistas" required>
            <option value="">-- Selecciona una pista --</option>
            <?php foreach ($pistas as $pista): ?>
                <option value="<?= esc($pista['id']) ?>" <?= isset($reservaEditando) && $reservaEditando['idPistas'] == $pista['id'] ? 'selected' : '' ?>>
                    <?= esc($pista['id']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Correo Cliente:</label>
        <select name="correoClientes" required>
            <option value="">-- Selecciona un cliente --</option>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?= esc($cliente['correo']) ?>" <?= isset($reservaEditando) && $reservaEditando['correoClientes'] == $cliente['correo'] ? 'selected' : '' ?>>
                    <?= esc($cliente['correo']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Fecha y Hora:</label>
        <input type="datetime-local" name="fechaHora"
            value="<?= isset($reservaEditando['fechaHora']) ? date('Y-m-d\TH:i', strtotime($reservaEditando['fechaHora'])) : '' ?>"
            required>

        <label>Número de Personas:</label>
        <input type="number" name="numPersonas" min="1" max="5" required
            value="<?= esc($reservaEditando['numPersonas'] ?? '') ?>">

        <label>Nivel Personas:</label>
        <input type="text" name="nivelPersonas" value="<?= esc($reservaEditando['nivelPersonas'] ?? '') ?>"
            placeholder="[2.5, 3]">

        <label>Media Nivel:</label>
        <input type="number" step="0.01" name="mediaNivel" min="0" max="5"
            value="<?= esc($reservaEditando['mediaNivel'] ?? '') ?>">

        <label>Estado Pago:</label>
        <select name="estadoPago">
            <option value="pendiente" <?= (isset($reservaEditando) && $reservaEditando['estadoPago'] === 'pendiente') ? 'selected' : '' ?>>Pendiente</option>
            <option value="pagado" <?= (isset($reservaEditando) && $reservaEditando['estadoPago'] === 'pagado') ? 'selected' : '' ?>>Pagado</option>
        </select>

        <label>Cancelada:</label>
        <select name="cancelada">
            <option value="0" <?= (isset($reservaEditando) && $reservaEditando['cancelada'] === '0') ? 'selected' : '' ?>>
                No</option>
            <option value="1" <?= (isset($reservaEditando) && $reservaEditando['cancelada'] === '1') ? 'selected' : '' ?>>
                Sí</option>
        </select>

        <button type="submit"><?= isset($reservaEditando) ? 'Actualizar' : 'Insertar' ?></button>
    </form>

    <input type="text" id="filtro" placeholder="Buscar por cualquier campo...">

    <table id="tablaReservas">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pista</th>
                <th>Cliente</th>
                <th>Fecha y Hora</th>
                <th>Nº Personas</th>
                <th>Nivel Personas</th>
                <th>Media Nivel</th>
                <th>Pago</th>
                <th>Cancelada</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservas as $reserva): ?>
                <tr>
                    <td><?= esc($reserva['id']) ?></td>
                    <td><?= esc($reserva['idPistas']) ?></td>
                    <td><?= esc($reserva['correoClientes']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($reserva['fechaHora'])) ?></td>
                    <td><?= esc($reserva['numPersonas']) ?></td>
                    <td><?= esc($reserva['nivelPersonas']) ?></td>
                    <td><?= esc($reserva['mediaNivel']) ?></td>
                    <td><?= esc($reserva['estadoPago']) ?></td>
                    <td><?= esc($reserva['cancelada']) == '1' ? 'Sí' : 'No' ?></td>
                    <td class="acciones">
                        <a href="<?= base_url('admin/pistasClientes?editar=' . $reserva['id']) ?>">Editar</a>
                        <a href="<?= base_url('admin/pistasClientes/eliminar?id=' . $reserva['id']) ?>"
                            onclick="return confirm('¿Deseas eliminar esta reserva?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        const filtro = document.getElementById('filtro');
        const filas = document.querySelectorAll('#tablaReservas tbody tr');

        filtro.addEventListener('keyup', () => {
            const texto = filtro.value.toLowerCase();
            filas.forEach(fila => {
                fila.style.display = fila.textContent.toLowerCase().includes(texto) ? '' : 'none';
            });
        });
    </script>

</body>

</html>