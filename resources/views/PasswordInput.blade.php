<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <style>
        body {
            font-family: 'Comic Sans MS', cursive, sans-serif;
            background-color: #F9EBFF;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
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
            font-size: 1.6rem;
            color: #FF828D;
            margin-bottom: 15px;
        }
        .body label {
            font-size: 1rem;
            color: #333;
            display: block;
            margin: 10px 0;
            text-align: left;
        }
        .body input {
            font-size: 1.2rem;
            padding: 12px;
            margin-bottom: 20px;
            width: calc(100% - 50px);
            border: 2px solid #ADD5D5;
            border-radius: 10px;
            outline: none;
            transition: border 0.3s;
        }
        .body input:focus {
            border-color: #8636CE;
        }
        .password-container {
            position: relative;
            display: flex;
            align-items: center;
        }
        .password-container button {
            position: absolute;
            right: 10px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
        }
        .body button {
            padding: 12px 24px;
            background-color: #8636CE;
            color: #FFFFFF;
            font-weight: bold;
            font-size: 1.2rem;
            border-radius: 10px;
            border: 2px solid #ADD5D5;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .body button:hover {
            background-color: #ADD5D5;
            color: #8636CE;
            border: 2px solid #8636CE;
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
        .bottom-left-image {
            position: absolute;
            bottom: 10px;
            left: 10px;
            width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            Restablecer Contraseña
        </div>
        <div class="body">
            <h2>Restablece tu Contraseña</h2>
            <form action="/restablecer" method="POST">
                <input type="hidden" name="userId" value="{{ $userId }}">
                
                <label for="password">Nueva Contraseña:</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" required>
                    <button type="button" onclick="togglePassword('password', this)">
                        <img src="/public/images/ojo-abierto.png" alt="Mostrar Contraseña" id="toggle-icon-password" width="20">
                    </button>
                </div>
                
                <label for="password_confirmation">Confirmar Contraseña:</label>
                <div class="password-container">
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                    <button type="button" onclick="togglePassword('password_confirmation', this)">
                        <img src="/public/images/ojo-abierto.png" alt="Mostrar Contraseña" id="toggle-icon-confirmation" width="20">
                    </button>
                </div>
                
                <button type="submit">Restablecer Contraseña</button>
            </form>
        </div>
        <div class="footer">
            <p>Gracias por confiar en nosotros</p>
        </div>
    </div>
    <img src="/public/images/Gamy_arriba_ojos.png" alt="Imagen decorativa" class="bottom-left-image" id="bottom-left-image">
    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('img');
            const bottomImage = document.getElementById('bottom-left-image');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.src = '/public/images/ojo-cerrado.png';
                icon.alt = 'Ocultar Contraseña';
                bottomImage.src = '/public/images/Gamy_cerrados_ojos.png'; // Cambiar a la imagen 2
            } else {
                input.type = 'password';
                icon.src = '/public/images/ojo-abierto.png';
                icon.alt = 'Mostrar Contraseña';
                bottomImage.src = '/public/images/Gamy_arriba_ojos.png'; // Regresar a la imagen 1
            }
        }
    </script>
</body>
</html>
