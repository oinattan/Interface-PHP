<?php
session_start(); // Inicia a sessão, se ainda não foi iniciada

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupere o nome do banco de dados da variável de sessão
    if (isset($_SESSION["database_name"])) {

        $localhost = "localhost";
        $username = "root";
        $password = "";
        $databaseName = $_SESSION["database_name"];
        
        $conn = new mysqli($localhost, $username, $password, $databaseName);
        
        if ($conn->connect_error) {
            die("Falha de Conexão: " . $conn->connect_error);
        }
        
        // Verifique se todos os campos obrigatórios estão preenchidos
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

?>