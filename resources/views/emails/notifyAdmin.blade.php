
<!DOCTYPE html>
<html>
<head>
    <title>Notificación de Nuevo Usuario</title>
</head>
<body>
    <h1>Notificación de Nuevo Usuario</h1>
    <p>Hola Administrador,</p>
    <p>Se ha activado una nueva cuenta de usuario con los siguientes datos:</p>
    <ul>
        <li><strong>Nombre:</strong> {{ $nombre }}</li>
        <li><strong>Apellido:</strong> {{ $apellido }}</li>
        <li><strong>Correo:</strong> {{ $email }}</li>
    </ul>
    <p>Por favor, revise los detalles y tome las acciones necesarias.</p>
    <p>Gracias,</p>
    <p>El equipo de soporte</p>
</body>
</html>