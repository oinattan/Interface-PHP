<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["database_name"])) {

        $localhost = "localhost";
        $username = "root";
        $password = "";
        $databaseName = $_SESSION["database_name"];

        $conn = new mysqli($localhost, $username, $password, $databaseName);

        if ($conn->connect_error) {
            die("Falha de Conexão: " . $conn->connect_error);
        }

        if (isset($_POST["text"]) && isset($_POST["number"]) && isset($_POST["date"]) && isset($_POST["color"])) {
            $texto = $_POST["text"];
            $number = $_POST["number"];
            $date = $_POST["date"];
            $color = $_POST["color"];

            $file = $_FILES["file"]["name"];

            $SQL = $conn->prepare("INSERT INTO REG(TEXT, NUMBER, DATE, COLOR, FILE) VALUES (?, ?, ?, ?, ?)");
            $SQL->bind_param("sisss", $texto, $number, $date, $color, $file);

            if ($SQL->execute()) {
                echo "Sucesso";
                echo "<a href='index.php' style='text-decoration: none; margin-left: 10px; padding: 20px;'><button>Voltar</button></a>";
            } else {
                echo "Erro: " . $SQL->error;
            }
        } else {
            echo "Certifique-se de preencher todos os campos obrigatórios.";
        }

        $conn->close();
    } else {
        echo "Nome do banco de dados não definido.";
    }
}
