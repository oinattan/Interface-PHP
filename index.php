<?php
session_start(); // Inicia a sessão, se ainda não foi iniciada

// Verifique se o nome do banco de dados está definido na sessão
if (isset($_SESSION["database_name"])) {
    $localhost = "localhost";
    $username = "root";
    $password = "";
    $databaseName = $_SESSION["database_name"];

    $conn = new mysqli($localhost, $username, $password, $databaseName);

    if ($conn->connect_error) {
        die("Falha de Conexão: " . $conn->connect_error);
    }
}

// Lógica para edição e exclusão de registros
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"]) && isset($_POST["id"])) {
        $action = $_POST["action"];
        $id = $_POST["id"];
        
        if ($action == "editar") {
            // Lógica para edição aqui

            // Verifique se todos os campos do formulário de edição foram enviados
            if (isset($_POST["text"]) && isset($_POST["number"]) && isset($_POST["date"]) && isset($_POST["color"])) {
                $texto = $_POST["text"];
                $numero = $_POST["number"];
                $data = $_POST["date"];
                $cor = $_POST["color"];

                // Crie a consulta SQL para atualizar o registro com base no ID
                $query = "UPDATE REG SET TEXT = '$texto', NUMBER = '$numero', DATE = '$data', COLOR = '$cor' WHERE ID = $id";

                // Execute a consulta de atualização
                if ($conn->query($query) === TRUE) {
                    echo "Registro atualizado com sucesso.";
                } else {
                    echo "Erro ao atualizar o registro: " . $conn->error;
                }
            } else {
                echo "Certifique-se de preencher todos os campos obrigatórios.";
            }
        } elseif ($action == "excluir") {
            // Lógica para exclusão aqui
            $query = "DELETE FROM REG WHERE ID = $id";
            if ($conn->query($query) === TRUE) {
                echo "Registro excluído com sucesso.";
            } else {
                echo "Erro ao excluir o registro: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface</title>
</head>
<body>
    <main>
        <h1>Envio de Resgitros, Edição e Exclusão</h1>
        <h3>Professor: Felipe</h3>
        <p>Disciplina: Algoritmos e técnicas de programação</p>
        <p>Aluno: Natan Gomes Biazon | Dev. Web - PHP</p>

        <h1>Criar novo Banco de Dados</h1>
        <!-- Formulário para criar um novo banco de dados -->
        <form action="criar_db.php" method="post">
            <label for="database_name">Nome do Banco de Dados:</label>
            <input type="text" name="database_name" id="database_name">
            <button type="submit">Enviar</button>
        </form>

        <h1>Enviar Registros para o DB</h1>
        <!-- Formulário para enviar registros -->
        <form action="process_envio.php" method="post" enctype="multipart/form-data">
            <label for="text">String</label>
            <input type="text" name="text">
            <label for="number">Int</label>
            <input type="number" name="number">
            <label for="date">Data</label>
            <input type="date" name="date">
            <label for="color">Cor</label>
            <input type="color" name="color">
            <label for="file">Arquivo</label>
            <input type="file" name="file">
            <button type="submit">Enviar</button>
        </form>

        <h1>Registros no Banco de Dados</h1>
        <?php
        // Consulta para selecionar todos os registros da tabela REG
        $query = "SELECT * FROM REG";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            // Exibir os registros em uma tabela com botões de edição e exclusão
            echo "<table border='1'>";
            echo "<tr><th>ID</th><th>Texto</th><th>Número</th><th>Data</th><th>Cor</th><th>Arquivo</th><th>Ações</th></tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["ID"] . "</td>";
                echo "<td>" . $row["TEXT"] . "</td>";
                echo "<td>" . $row["NUMBER"] . "</td>";
                echo "<td>" . $row["DATE"] . "</td>";
                echo "<td>" . $row["COLOR"] . "</td>";
                echo "<td><a href='download.php?id=" . $row["ID"] . "'>" . $row["FILE"] . "</a></td>";
                echo "<td>";
                echo "<form action='' method='post'>";
                echo "<input type='hidden' name='id' value='" . $row["ID"] . "'>";
                echo "<input type='hidden' name='action' value='editar'>";
                echo "<button type='submit'><a href='editar_registro.php?id=" . $row["ID"] . "'>Editar</a></button>";
                echo "</form>";
                echo "<form action='' method='post'>";
                echo "<input type='hidden' name='id' value='" . $row["ID"] . "'>";
                echo "<input type='hidden' name='action' value='excluir'>";
                echo "<button type='submit'>Excluir</button>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
            
            echo "</table>";
        } else {
            echo "Nenhum registro encontrado.";
        }
        ?>
    </main>
</body>
</html>
