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
    if (isset($_POST["id"])) {
        $id = $_POST["id"];

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
    }
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $query = "SELECT * FROM REG WHERE ID = $id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $textoAtual = $row["TEXT"];
        $numeroAtual = $row["NUMBER"];
        $dataAtual = $row["DATE"];
        $corAtual = $row["COLOR"];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Registro</title>
</head>
<body>
    <main>
        <h1>Editar Registro</h1>
        <form action="" method="post">
            <label for="text">String</label>
            <input type="text" name="text" id="text" value="<?php echo $textoAtual; ?>">
            <label for="number">Int</label>
            <input type="number" name="number" id="number" value="<?php echo $numeroAtual; ?>">
            <label for="date">Data</label>
            <input type="date" name="date" id="date" value="<?php echo $dataAtual; ?>">
            <label for="color">Cor</label>
            <input type="color" name="color" id="color" value="<?php echo $corAtual; ?>">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <button type="submit">Salvar Edição</button>
        </form>
    </main>
</body>
</html>
