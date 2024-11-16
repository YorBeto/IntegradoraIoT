<!DOCTYPE html>
<html>
<head>
    <title>Activación de Cuenta</title>
</head>
<body>
    <h2>Hola {{ $persona->nombre }} {{ $persona->apellido_paterno }}</h2>
    <p>Gracias por registrarte. Haz clic en el siguiente enlace para activar tu cuenta:</p>
    <a href="{{ $activationLink }}">Activar mi cuenta</a>
</body>
</html>
