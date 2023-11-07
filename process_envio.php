<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["database_name"])) {

        $localhost = "localhost";
        $username = "u724950182_system";
        $password = "Teste@teste01";
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
