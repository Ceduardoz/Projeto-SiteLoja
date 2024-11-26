<?php
// Configuração do banco de dados
$host = "localhost";
$user = "root";
$password = ""; 
$dbname = "loja_virtual";

// Conexão com o banco de dados
$conn = new mysqli($host, $user, $password, $dbname);

// Verifica conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// cadastrar o usuário
function cadastrarUsuario($conn, $dados) {
    $nome = mysqli_real_escape_string($conn, $dados['nome']);
    $cpf = mysqli_real_escape_string($conn, $dados['cpf']);
    $telefone = mysqli_real_escape_string($conn, $dados['tel']);
    $endereco = mysqli_real_escape_string($conn, $dados['endereco']);
    $email = mysqli_real_escape_string($conn, $dados['email']);
    $senha = $dados['password'];
    $confirm_senha = $dados['confirm-password'];

    // Verifica se as senhas coincidem
    if ($senha !== $confirm_senha) {
        echo "<script>alert('Erro: As senhas não coincidem. Por favor, tente novamente.');</script>";
        echo "<script>window.history.back();</script>"; 
        exit();
    }

    // Criptografa a senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Prepara a consulta SQL diretamente
    $sql = "INSERT INTO usuarios (nome, cpf, telefone, endereco, email, senha) 
            VALUES ('$nome', '$cpf', '$telefone', '$endereco', '$email', '$senha_hash')";

    // Executa a consulta
    if (mysqli_query($conn, $sql)) {
        // Redirecionamento para a página inicial 
        header("Location: ../index.html");
        exit(); 
    } else {
        echo "<script>alert('Erro ao cadastrar: " . mysqli_error($conn) . "');</script>";
    }
}

// autenticar o usuário
function loginUsuario($conn, $dados) {
    $email = mysqli_real_escape_string($conn, $dados['email']);
    $senha = $dados['password'];

    // Consultar o e-mail no banco de dados
    $sql = "SELECT senha FROM usuarios WHERE email = '$email'";

    $result = mysqli_query($conn, $sql);

    // Verificar se o usuário foi encontrado
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $senha_hash = $row['senha'];

        // Verifica a senha
        if (password_verify($senha, $senha_hash)) {
            // Redirecionamento para a página inicial
            header("Location: ../index.html");
            exit(); 
        } else {
            echo "<script>alert('Erro: Senha incorreta.');</script>";
            echo "<script>window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Erro: Usuário não encontrado.');</script>";
        echo "<script>window.history.back();</script>";
    }
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
