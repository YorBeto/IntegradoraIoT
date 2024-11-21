<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta Activada</title>
    <style>
        body {
            font-family: 'Comic Sans MS', cursive, sans-serif;
            background-color: #f0f8ff;
            margin: 0;
            padding: 0;
            text-align: center;
            overflow: hidden;
        }
        .container {
            padding: 20px;
            margin: 50px auto;
            max-width: 600px;
            background: #ffebcd;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
        }
        .header {
            font-size: 2.5rem;
            color: #ff4500;
        }
        .message {
            font-size: 1.2rem;
            color: #333;
        }
        .image {
            margin: 20px 0;
        }
        .image img {
            max-width: 200px;
            border-radius: 50%;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            font-size: 1rem;
            background-color: #32cd32;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .button:hover {
            background-color: #228b22;
        }
        /* Estilos para los confetis */
        .confetti {
            position: fixed;
            top: 0;
            width: 10px;
            height: 10px;
            background-color: #ff4500;
            border-radius: 50%;
            animation: fall 5s linear infinite, sway 2s ease-in-out infinite;
        }
        .confetti:nth-child(odd) {
            background-color: #ffd700;
        }
        @keyframes fall {
            from {
                transform: translateY(-10%);
            }
            to {
                transform: translateY(110%);
            }
        }
        @keyframes sway {
            from {
                transform: translateX(-10px);
            }
            to {
                transform: translateX(10px);
            }
        }
    </style>
</head>
<body>
    <!-- Contenedor del confeti -->
    <div id="confetti-wrapper"></div>
    <div class="container">
        <h1 class="header">¡Felicidades!</h1>
        <div class="image">
        <img src="{{ asset('images/GamyG1.png') }}" alt="Cuenta Activada">
        </div>
        <p class="message">Tu cuenta ha sido activada con éxito. Ahora puedes explorar todas nuestras funciones y divertirte aprendiendo.</p>
    </div>

    <script>
        const confettiWrapper = document.getElementById('confetti-wrapper');

        function createConfetti() {
            const confetti = document.createElement('div');
            confetti.classList.add('confetti');
            confetti.style.left = Math.random() * 100 + 'vw';
            confetti.style.animationDelay = Math.random() * 5 + 's';
            confettiWrapper.appendChild(confetti);

            // Eliminar confeti después de la animación
            setTimeout(() => confetti.remove(), 5000);
        }

        // Crear múltiples confetis
        for (let i = 0; i < 100; i++) {
            createConfetti();
        }

        // Generar confeti adicional periódicamente
        setInterval(createConfetti, 300);
    </script>
</body>
</html>
