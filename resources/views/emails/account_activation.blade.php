<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activación de Cuenta</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #F9EBFF;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 100%;
            max-width: 500px;
            background-color: #FFFFFF;
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            border: 3px solid #ADD5D5;
        }
        .header {
            background-color: #8636CE;
            color: #FFFFFF;
            text-align: center;
            padding: 20px;
            font-size: 1.8rem;
            font-weight: bold;
        }
        .body {
            padding: 30px 20px;
            text-align: center;
        }
        .body h2 {
            font-size: 1.4rem;
            color: #FF828D;
            margin-bottom: 15px;
        }
        .body p {
            font-size: 1rem;
            color: #333;
            margin: 15px 0;
            line-height: 1.5;
        }
        .body a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #8636CE;
            color: #FFFFFF;
            text-decoration: none;
            font-weight: bold;
            font-size: 1rem;
            border-radius: 10px;
            border: 2px solid #ADD5D5;
            transition: all 0.3s ease;
        }
        .body a:hover {
            background-color: #ADD5D5;
            color: #8636CE;
            border: 2px solid #8636CE;
        }
        .illustration {
            text-align: center;
            margin: 20px 0;
        }
        .illustration img {
            width: 120px;
            border-radius: 15px;
            border: 2px solid #ADD5D5;
        }
        .footer {
            background-color: #ADD5D5;
            color: #333;
            padding: 15px;
            font-size: 0.9rem;
            text-align: center;
        }
        .footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            ¡Activa tu aventura!
        </div>
        <div class="body">
            <h2>Hola, {{ $persona->nombre }} {{ $persona->apellido_paterno }} 🎉</h2>
            <div class="illustration">
                <img src="{{ url('/image') }}" alt="Imagen de bienvenida">
            </div>
            <p>Gracias por registrarte en nuestra app mágica. Haz clic en el botón a continuación para activar tu cuenta y comenzar la diversión:</p>
            <a href="{{ $activationLink }}">Activar mi cuenta</a>
        </div>
        <div class="footer">
            <p>Estamos emocionados de tenerte aquí 🥳</p>
        </div>
    </div>
</body>
</html>