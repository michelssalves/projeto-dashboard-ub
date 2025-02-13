<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    /* Reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Arial', sans-serif;
        background-color: #3d3939;
        color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        padding: 20px;
    }

    .container {
        display: flex;
        flex-wrap: wrap;
        max-width: 1200px;
        width: 100%;
        background-color: #ffffff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.5);
    }

    .left-panel {
        flex: 1;
        padding: 40px;
        text-align: center;
        background-color: #FFFFFF;
        color: #FFFFFF;
    }

    .left-panel h1 {
        font-size: 2.5rem;
        margin-bottom: 20px;
        color: #FF7F1E;
    }

    .left-panel p {
        font-size: 1.2rem;
        margin-bottom: 40px;
    }

    .left-panel img {
        max-width: 100%;
        height: auto;
        border-radius: 15px;
        filter: brightness(1.2) contrast(1.1) saturate(1.2);
    }

    .right-panel {
        flex: 1;
        padding: 40px;
        background-color: #7F3FC9;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .right-panel form {
        width: 100%;
        max-width: 350px;
        text-align: center;
    }

    .right-panel h2 {
        font-size: 2rem;
        margin-bottom: 20px;
        color: #FF7F1E;
    }

    .form-group {
        margin-bottom: 20px;
        text-align: left;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-size: 1rem;
        color: #7F3FC9;
    }

    input {
        width: 100%;
        padding: 10px;
        font-size: 1rem;
        border: 2px solid #9E59D1;
        border-radius: 8px;
        background-color: #f9f9f9;
        color: #333333;
    }

    input::placeholder {
        color: #999999;
        opacity: 0.8;
    }

    button {
        width: 100%;
        padding: 12px;
        font-size: 1rem;
        border: none;
        border-radius: 8px;
        background-color: #FF7F1E;
        color: #FFFFFF;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    button:hover {
        background-color: #E96F16;
        transform: scale(1.02);
    }

    .register-link {
        margin-top: 20px;
        font-size: 0.9rem;
    }

    .register-link a {
        color: #FF7F1E;
        text-decoration: none;
        font-weight: bold;
    }

    .register-link a:hover {
        text-decoration: underline;
    }

    .error-messages {
        margin-bottom: 20px;
        padding: 10px;
        background-color: #FFDDDD;
        color: #D8000C;
        border: 1px solid #D8000C;
        border-radius: 8px;
        font-size: 0.9rem;
    }

    .success-messages {
        margin-bottom: 20px;
        padding: 10px;
        background-color: #DDFFDD;
        color: #28A745;
        border: 1px solid #28A745;
        border-radius: 8px;
        font-size: 0.9rem;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .container {
            flex-direction: column;
        }

        .left-panel,
        .right-panel {
            flex: none;
            width: 100%;
        }
    }
</style>

<body>
    <div class="container">
        <div class="left-panel">
            <h1>Bem-vindo</h1>
            <p><strong></strong></p>
            <img src="{{ url('./public/images/imagem-gamer.jpg') }}" alt="Imagem ilustrativa" />
        </div>
        <div class="right-panel">
            <form method="POST" action="{{ route('login.process') }}">
                @csrf
                <h2>LOGIN</h2>
                @if ($errors->any())
                    <div class="error-messages">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('success'))
                    <div class="success-messages">
                        <ul>
                            <li>{{ session('success') }}</li>
                        </ul>
                    </div>
                @endif
                <div class="form-group">
                    <label for="usuario">Usuário</label>
                    <input type="email" name="email" id="usuario" placeholder="Digite seu email" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" name="password" id="senha" placeholder="Digite sua senha" required>
                </div>
                <button type="submit">LOGIN</button>
                <div class="register-link">
                </div>
            </form>
        </div>
    </div>
</body>

</html>
