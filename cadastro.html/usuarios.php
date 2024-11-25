<?php
// Configuração do banco de dados
$host = "localhost";
$user = "root"; // Usuário do banco de dados
$password = ""; // Senha do banco de dados
$dbname = "cadastro_db";

// Conexão com o banco de dados
$conn = new mysqli($host, $user, $password, $dbname);

// Verifica conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Função para cadastrar o usuário
function cadastrarUsuario($conn, $dados) {
    $nome = $dados['nome'];
    $cpf = $dados['cpf'];
    $telefone = $dados['tel'];
    $endereco = $dados['endereco'];
    $email = $dados['email'];
    $senha = $dados['password'];
    $confirm_senha = $dados['confirm-password'];
    $genero = $dados['genero'];

    // Verifica se as senhas coincidem
    if ($senha !== $confirm_senha) {
        echo "<script>alert('Erro: As senhas não coincidem. Por favor, tente novamente.');</script>";
        echo "<script>window.history.back();</script>"; // Volta para o formulário
        exit();
    }

    // Criptografa a senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Prepara a consulta SQL
    $sql = "INSERT INTO usuarios (nome, cpf, telefone, endereco, email, senha, genero) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $nome, $cpf, $telefone, $endereco, $email, $senha_hash, $genero);

    // Executa a consulta
    if ($stmt->execute()) {
        echo "<script>alert('Cadastro realizado com sucesso!');</script>";
        echo "<script>window.location.href = 'index.html';</script>"; // Redireciona para a página inicial
    } else {
        echo "<script>alert('Erro ao cadastrar: " . $conn->error . "');</script>";
    }

    $stmt->close();
}

// Função para autenticar o usuário
function loginUsuario($conn, $dados) {
    $email = $dados['email'];
    $senha = $dados['password'];

    // Prepara a consulta SQL
    $sql = "SELECT senha FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Verifica se o usuário foi encontrado
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($senha_hash);
        $stmt->fetch();

        // Verifica a senha
        if (password_verify($senha, $senha_hash)) {
            echo "<script>alert('Login realizado com sucesso!');</script>";
            echo "<script>window.location.href = 'index.html';</script>"; // Redireciona para a página inicial
        } else {
            echo "<script>alert('Erro: Senha incorreta.');</script>";
            echo "<script>window.history.back();</script>"; // Volta para o formulário
        }
    } else {
        echo "<script>alert('Erro: Usuário não encontrado.');</script>";
        echo "<script>window.history.back();</script>"; // Volta para o formulário
    }

    $stmt->close();
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'cadastrar') {
            cadastrarUsuario($conn, $_POST);
        } elseif ($_POST['action'] === 'login') {
            loginUsuario($conn, $_POST);
        }
    }
}

$conn->close();
?>
