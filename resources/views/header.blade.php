<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitações de Compra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<style>
    /* Reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html,
    body {
        margin: 0;
        padding: 0;
        height: 100%;
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
        /* Garante que ocupa toda a altura */
    }

    /* Estilo principal do dropdown */
    .dropdown-menu {
        background-color: #7F3FC9;
        /* Roxo para fundo */
        border: none;
        /* Remove bordas padrão */
        border-radius: 8px;
        /* Bordas arredondadas */
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        /* Sombra suave */
        padding: 0.5rem 0;
        /* Espaçamento interno */
    }

    /* Estilo dos itens do dropdown */
    .dropdown-item {
        color: #FFFFFF;
        /* Texto branco */
        padding: 10px 20px;
        /* Espaçamento interno */
        font-weight: bold;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    /* Hover nos itens */
    .dropdown-item:hover {
        background-color: #fa9f59;
        /* Cor laranja no hover */
        color: #FFFFFF;
        /* Garante texto branco */
        transform: scale(1.05);
        /* Efeito de zoom leve */
    }

    /* Espaçamento entre os itens */
    .dropdown-item+.dropdown-item {
        margin-top: 5px;
    }

    /* Seta personalizada para dropdown */
    .nav-link.dropdown-toggle::after {
        content: '\25BC';
        /* Adiciona seta para baixo */
        font-size: 0.6rem;
        margin-left: 5px;
        color: #FFFFFF;
        /* Seta branca */
        transition: transform 0.2s ease;
    }

    /* Gira a seta no hover */
    .nav-link.dropdown-toggle:hover::after {
        transform: rotate(180deg);
    }

    /* Ajuste do texto no link principal */
    .nav-link {
        color: #FFFFFF;
        font-weight: bold;
    }

    .navbar {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        background-color: #8735ff;

    }

    /* Botão de Logout */
    .btn-outline-light {
        border-color: white;
        color: white;
    }

    /* Estilo para o botão de logout */
    .logout-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: transparent;
        border: none;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        transition: transform 0.2s ease, color 0.3s ease;

    }

    .logout-btn:hover {
        transform: scale(1.1);
        /* Efeito de zoom ao passar o mouse */
        color: #ffdddd;
        /* Cor ao passar o mouse */
    }

    /* Alinhamento do botão no menu */
    .logout {
        margin-left: auto;
        /* Alinha o botão de logout à direita */
    }

    main {
        margin-top: 20px;
        margin: 20px 40px;
        /* 20px de margem superior/inferior e 40px de margem lateral */
        padding: 20px;
        /* Espaçamento interno para o conteúdo */
        background-color: #ffffff;
        /* Fundo branco para destaque */
        border-radius: 8px;
        /* Bordas arredondadas */
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        /* Sombra suave */
        color: #333;
        /* Cor do texto */
    }

    .table th,
    .table td {
        vertical-align: middle;
        text-align: center;
    }

    /* Ajuste de Badge (Status) */
    .badge {
        font-size: 0.9rem;
        padding: 0.5em 1em;
    }

    .btn-icon {
        width: 50px;
        height: 40px;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1.2rem;
    }

    #cart-counter {
        font-size: 0.75rem;
        padding: 6px 6px;
        border: 2px solid white;
        color: white;
    }

    .btn-remove-item i {
        font-size: 1rem;
    }

    .message-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .message-content {
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        text-align: center;
        width: 300px;
    }

    .message-icon {
        font-size: 50px;
        margin-bottom: 15px;
    }

    .message-text {
        font-size: 18px;
        font-weight: bold;
        margin: 0;
    }

    .success-icon {
        color: green;
    }

    .error-icon {
        color: red;
    }
</style>
<div id="message" style="display: none;" class="message-overlay">
    <div class="message-content">
        <div id="message-icon" class="message-icon"></div>
        <p id="message-text"></p>
    </div>
</div>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <!-- Marca -->
        <a class="nav-link dropdown" href="{{ route('home') }}"><img style="width: 60px ;height:25px;padding-right:3px"
                src="{{ url('./public/images/imagem-gamer.jpg') }}" alt="Imagem ilustrativa" /></a>

        <!-- Botão de Toggle para Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Itens do Menu -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="comprasDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Eventos
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="comprasDropdown">
                        <li><a class="dropdown-item" href="{{ route('eventos.index') }}">Listar</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="comprasDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Config
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="comprasDropdown">
                        <li><i class="dropdown-item" data-bs-toggle="modal"
                                data-bs-target="#modalRegister">Registrar-se</i></li>
                    </ul>
                </li>

            </ul>

            <!-- Botão de Logout -->
            <form class="d-flex" method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light d-flex align-items-center">
                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                </button>
            </form>
        </div>
    </div>
</nav>
<!-- Modal para Filtros -->
<div class="modal fade" id="modalRegister" tabindex="-1" aria-labelledby="modalRegisterLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRegisterLabel">Registrar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form method="POST" action="{{ route('register.process') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" id="name" name="name" class="form-control"
                            placeholder="Digite seu nome" required>
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control"
                            placeholder="Digite seu email" required>
                        <label for="email" class="form-label">Senha</label>
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Digite sua senha" required>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
