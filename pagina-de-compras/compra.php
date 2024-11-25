<?php
// Configuração do banco de dados
$servername = "localhost";
$username = "root";  // Seu usuário do banco de dados
$password = "";  // Sua senha do banco de dados
$dbname = "bilheteira";

// Função para conectar ao banco de dados
function conectarBanco() {
    global $servername, $username, $password, $dbname;
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Função para verificar a quantidade de ingressos disponíveis
function verificarIngressosDisponiveis($conn, $evento_id, $quantidade) {
    $sql = "SELECT ingressos_disponiveis FROM eventos WHERE id = $evento_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['ingressos_disponiveis'] >= $quantidade) {
            return true;
        }
    }
    return false;
}

// Função para registrar a compra no banco de dados
function registrarCompra($conn, $evento_id, $nome, $email, $quantidade, $tipo, $pagamento) {
    $sql_insert = "INSERT INTO compras (evento_id, nome, email, quantidade, tipo_ingresso, pagamento)
                   VALUES ($evento_id, '$nome', '$email', $quantidade, '$tipo', '$pagamento')";
    if ($conn->query($sql_insert) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Função para atualizar a quantidade de ingressos disponíveis
function atualizarIngressos($conn, $evento_id, $quantidade) {
    $sql = "UPDATE eventos SET ingressos_disponiveis = ingressos_disponiveis - $quantidade WHERE id = $evento_id";
    return $conn->query($sql);
}

// Função para processar a compra
function processarCompra($evento_id, $nome, $email, $quantidade, $tipo, $pagamento) {
    $conn = conectarBanco();

    if (verificarIngressosDisponiveis($conn, $evento_id, $quantidade)) {
        if (registrarCompra($conn, $evento_id, $nome, $email, $quantidade, $tipo, $pagamento)) {
            if (atualizarIngressos($conn, $evento_id, $quantidade)) {
                echo "Compra realizada com sucesso!";
            } else {
                echo "Erro ao atualizar a quantidade de ingressos!";
            }
        } else {
            echo "Erro ao registrar a compra!";
        }
    } else {
        echo "Ingressos esgotados!";
    }

    $conn->close();
}

// Processar o formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $quantidade = $_POST['quantidade'];
    $tipo = $_POST['tipo'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $pagamento = $_POST['pagamento'];
    
    // ID do evento (aqui, você precisa de um evento específico)
    $evento_id = 1;  // Exemplo, substitua pelo ID do evento real

    processarCompra($evento_id, $nome, $email, $quantidade, $tipo, $pagamento);
}
?>
