<?php
session_start();

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"]) && isset($_POST["id"])) {
        $action = $_POST["action"];
        $id = $_POST["id"];

        if ($action == "editar") {

            if (isset($_POST["text"]) && isset($_POST["number"]) && isset($_POST["date"]) && isset($_POST["color"])) {
                $texto = $_POST["text"];
                $numero = $_POST["number"];
                $data = $_POST["date"];
                $cor = $_POST["color"];

                $query = "UPDATE REG SET TEXT = '$texto', NUMBER = '$numero', DATE = '$data', COLOR = '$cor' WHERE ID = $id";

                if ($conn->query($query) === TRUE) {
                    echo "Registro atualizado com sucesso.";
                } else {
                    echo "Erro ao atualizar o registro: " . $conn->error;
                }
            } else {
                echo "Certifique-se de preencher todos os campos obrigatórios.";
            }
        } elseif ($action == "excluir") {
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
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <main>
        <h1>Envio de Registros, Edição e Exclusão</h1>
        <div class="informacoes">
            <p><strong>Professor:</strong> Felipe</p>
            <p><strong>Disciplina:</strong> Algoritmos e Técnicas de Programação</p>
            <p><strong>Aluno:</strong> Natan Gomes Biazon | Dev. Web - PHP</p>
        </div>

        <h2>Criar novo Banco de Dados</h2>

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
            <input type="text" name="text" pattern="^[a-zA-Z\s]+$">
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
        $query = "SELECT * FROM REG";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
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
                // Botão Editar
                
                echo "<input type='hidden' name='id' value='" . $row["ID"] . "'>";
                echo '<a href="editar_registro.php?id=' . $row["ID"] . '" class="editar-button">Editar</a>';

         
                // Botão Excluir
                echo "<form action='' method='post' style='display: inline;'>";
    echo "<input type='hidden' name='id' value='" . $row["ID"] . "'>";
    echo "<input type='hidden' name='action' value='excluir'>";
    echo "<button type='submit' class='excluir-button'>Excluir</button>";
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