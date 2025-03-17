<?php
require 'db.php';
require 'session.php';

if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "As senhas não coincidem.";
    } else {
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $error = "Nome de usuário já existe. Escolha outro.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hashed_password]);

            $_SESSION['user'] = $username;

            header("Location: index.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <style>
        .login-container {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .btn-login {
            background-color: #f8b400;
            color: white;
            font-weight: bold;
            border-radius: 25px;
        }

        .btn-login:hover {
            background-color: #e68900;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/"><i class="fas fa-comments"></i> Chatbot Transparente</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Alternar navegação">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/"><i class="fas fa-home"></i> Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php"><i class="fas fa-info-circle"></i> Sobre</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php"><i class="fas fa-envelope"></i> Contato</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="login-container mt-4">
        <h2 class="mb-4"><i class="fas fa-user-plus"></i> Criar Conta</h2>

        <?php if (isset($error))
            echo "<div class='alert alert-danger'>$error</div>"; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nome de Usuário:</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" name="username" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Senha:</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirmar Senha:</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="btn btn-login w-100"><i class="fas fa-user-plus"></i> Criar Conta</button>
        </form>
        <div class="mt-3">
            <p>Já tem uma conta? <a href="login.php"><i class="fas fa-sign-in-alt"></i> Faça login aqui</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>