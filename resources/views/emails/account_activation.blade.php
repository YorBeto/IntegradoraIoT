<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activación de Cuenta</title>
    <style>
        body {
            font-family: 'Comic Sans MS', Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 3px solid #fdd835;
        }
        .header {
            background-color: #fdd835;
            color: #fff;
            padding: 20px;
            text-align: center;
            font-size: 1.8rem;
            font-weight: bold;
        }
        .body {
            padding: 20px;
            text-align: center;
        }
        .body h2 {
            font-size: 1.5rem;
            color: #333;
        }
        .body p {
            font-size: 1rem;
            color: #555;
            margin: 15px 0;
        }
        .body a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4caf50;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            font-size: 1rem;
        }
        .body a:hover {
            background-color: #388e3c;
        }
        .footer {
            background-color: #eeeeee;
            color: #888;
            padding: 15px;
            font-size: 0.9rem;
            text-align: center;
        }
        .footer img {
            width: 40px;
            vertical-align: middle;
            margin-right: 10px;
        }
        .illustration {
            text-align: center;
            margin: 20px 0;
        }
        .illustration img {
            width: 150px;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            ¡Bienvenido a nuestra aventura!
        </div>
        <div class="body">
            <h2>Hola, {{ $persona->nombre }} {{ $persona->apellido_paterno }} 🎉</h2>
            <div class="illustration">
            </div>
            <p>Gracias por registrarte en nuestra app mágica. Haz clic en el botón a continuación para activar tu cuenta y empezar la diversión:</p>
            <a href="{{ $activationLink }}">Activar mi cuenta</a>
        </div>
        <div class="footer">
            <p>¡Nos encanta tenerte aquí! 🥳</p>
            <img src="https://via.placeholder.com/40/ff6f61/ffffff?text=🎈" alt="Ícono de diversión"> Equipo de Aventuras
        </div>
    </div>
</body>
</html>
