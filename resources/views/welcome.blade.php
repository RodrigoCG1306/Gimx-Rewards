<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to GIMX Rewards</title>
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #2D333B; /* Color de fondo en caso de que la imagen no cargue */
        }

        .background-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("https://25174313.fs1.hubspotusercontent-eu1.net/hubfs/25174313/Travel%20Insurance%205%20Things.png");
            background-size: cover;
            filter: blur(1px);
            background-repeat: no-repeat;
            background-position: center;
        }

        .content {
            position: relative;
            max-width: 600px;
            padding: 40px;
            margin: auto;
            background-color: rgba(255, 255, 255, 20);
            border-radius: 10px;
            text-align: center;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: 600;
            color: #fa5a5a;
            margin-bottom: 20px;
        }

        p {
            font-size: 1.2rem;
            line-height: 1.6;
            color: #00002e;
        }

        a {
            color: #55a4ff;
        }

        a:hover {
            color: #137dff;
        }

        .img {
            width: 120px;
        }

        /* Estilos para los botones */
        .login {
            background-color: #55a4ff;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login:hover {
            background-color: #137dff;
        }

    </style>
</head>

<body>
    <div class="background-container"></div>
    <div class="content">
        <x-application-logo class="img" />
        <p>
            ¡Bienvenido a GIMX Rewards! Comienza a ganar recompensas hoy participando en nuestro emocionante programa.
            <br>
            @auth
            <button type="button" class="login" onclick="location.href='/login'">
                Dashboard
            </button>
            @else
            <button type="button" class="login" onclick="location.href='/login'">
                Log in
            </button>
            @endauth
        </p>
    </div>
</body>

</html>
