<?php
// Configuração do banco de dados
$servername = "localhost";
$username = "root"; 
$password = "";  
$dbname = "loja_virtual";

// conectar ao banco de dados
function conectarBanco() {
    global $servername, $username, $password, $dbname;
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Verificar ingressos disponíveis para um evento específico
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

// Registrar a compra no banco de dados
function registrarCompra($conn, $evento_id, $nome, $email, $quantidade, $tipo, $pagamento) {
    $sql_insert = "INSERT INTO compras (evento_id, nome, email, quantidade, tipo_ingresso, pagamento)
                   VALUES ($evento_id, '$nome', '$email', $quantidade, '$tipo', '$pagamento')";
    if ($conn->query($sql_insert) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Atualizar a quantidade de ingressos disponíveis
function atualizarIngressos($conn, $evento_id, $quantidade) {
    $sql = "UPDATE eventos SET ingressos_disponiveis = ingressos_disponiveis - $quantidade WHERE id = $evento_id";
    return $conn->query($sql);
}

// Processar a compra
function processarCompra($evento_id, $nome, $email, $quantidade, $tipo, $pagamento) {
    $conn = conectarBanco();

    // Verifica se os ingressos estão disponíveis
    if (verificarIngressosDisponiveis($conn, $evento_id, $quantidade)) {
        // Registra a compra no banco de dados
        if (registrarCompra($conn, $evento_id, $nome, $email, $quantidade, $tipo, $pagamento)) {
            // Atualiza a quantidade de ingressos no banco de dados
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
    
    
    $evento_id = 1 ;

    processarCompra($evento_id, $nome, $email, $quantidade, $tipo, $pagamento);
}
?>