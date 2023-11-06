<?php
session_start(); // Inicia a sessão, se ainda não foi iniciada

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["database_name"]) && !empty($_POST["database_name"])) {
        $localhost = "localhost";
        $username = "root";
        $password = "";
        $databaseName = $_POST["database_name"];

        $_SESSION["database_name"] = $databaseName;

        $conn = new mysqli($localhost, $username, $password);

        if ($conn->connect_error) {
            die("Falha de Conexão: " . $conn->connect_error);
        }

        $createDatabaseSQL = "CREATE DATABASE IF NOT EXISTS `$databaseName`";
        if ($conn->query($createDatabaseSQL) === TRUE) {
            // Selecionar o banco de dados recém-criado
            $conn->select_db($databaseName);

            $createTableSQL = "CREATE TABLE IF NOT EXISTS REG (
                ID INT AUTO_INCREMENT PRIMARY KEY,
                TEXT VARCHAR(255) NOT NULL,
                NUMBER INT NOT NULL,
                DATE DATE NOT NULL,
                COLOR VARCHAR(7) NOT NULL,
                FILE LONGBLOB NOT NULL
            )";
            if ($conn->query($createTableSQL) === TRUE) {
                echo "Banco de dados '$databaseName' e tabela 'REG' criados com sucesso.<br>";
            } else {
                echo "Erro ao criar a tabela 'REG': " . $conn->error . "<br>";
            }
        } else {
            echo "Erro ao criar o banco de dados: " . $conn->error . "<br>";
        }

        $conn->close();
    } else {
        echo "Certifique-se de preencher o nome do banco de dados.";
    }
}
?>
