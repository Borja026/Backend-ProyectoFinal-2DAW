<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        nav {
            background-color: #343a40;
            padding: 15px 20px;
            margin-bottom: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);

            & ul {
                list-style: none;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;

                & li {
                    margin: 0 15px;

                    & a {
                        text-decoration: none;
                        color: #ffffff;
                        font-weight: bold;
                        padding: 8px 12px;
                        border-radius: 4px;
                        transition: background-color 0.3s ease;

                        &:hover {
                            background-color: #495057;
                        }
                    }
                }
            }
        }
    </style>
</head>

<body>
    <script>
        // Verificación de sesión
        const correo = localStorage.getItem('usuarioCorreo');
        const tipo = localStorage.getItem('usuarioTipo');

        if (!correo || tipo !== 'empleado') {
            // Redirige automáticamente si no hay sesión
            window.location.href = "https://borja.com.es/ProyectoDosDAW/#/login";
        }

        function cerrarSesion() {
            localStorage.removeItem('usuarioCorreo');
            localStorage.removeItem('usuarioTipo');
            window.location.href = "https://borja.com.es/ProyectoDosDAW/#/login";
        }
    </script>


    <nav>
        <ul>
            <li><a href="<?= base_url('admin/clientes') ?>">Clientes</a></li>
            <li><a href="<?= base_url('admin/empleados') ?>">Empleados</a></li>
            <li><a href="<?= base_url('admin/pistasClientes') ?>">Reservas</a></li>
            <li><a href="<?= base_url('admin/pistas') ?>">Pistas</a></li>
            <li><a href="<?= base_url('admin/galeria') ?>">Galeria</a></li>
            <li><a href="<?= base_url('admin/categorias') ?>">Categorias</a></li>
            <li><a href="#" onclick="cerrarSesion()">Cerrar sesión</a></li>
        </ul>
    </nav>
</body>

</html>