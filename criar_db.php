<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $localhost = "localhost";
    $username = "u724950182_system";
    $password = "Teste@teste01";
    $databaseName = "u724950182_arc"; // Nome do banco de dados padrão

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
            echo "<div style='background-color: #f0f0f0; border: 1px solid #ddd; padding: 10px;'>";
            echo "<p style='font-weight: bold; margin-bottom: 10px;'>Banco de dados '$databaseName' e tabela 'REG' criados com sucesso.</p>";
            echo "<p style='font-weight: bold; margin-bottom: 10px;'>Aqui está o código SQL de inserção:</p>";
            echo "<pre style='background-color: #fff; border: 1px solid #ccc; padding: 10px;'>";
            echo "<code id='sql-code'>";
            echo "CREATE TABLE IF NOT EXISTS REG (\n";
            echo "    ID INT AUTO_INCREMENT PRIMARY KEY,\n";
            echo "    TEXT VARCHAR(255) NOT NULL,\n";
            echo "    NUMBER INT NOT NULL,\n";
            echo "    DATE DATE NOT NULL,\n";
            echo "    COLOR VARCHAR(7) NOT NULL,\n";
            echo "    FILE LONGBLOB NOT NULL\n";
            echo ");";
            echo "</code>";
            echo "</pre>";
            echo "<a href='index.php' style='text-decoration: none; margin-left: 10px; padding: 20px;'><button>Voltar</button></a>";
            echo "<button id='copy-button' onclick='copyToClipboard()'>Copiar</button>";
            echo "</div>";

            echo "<script>
            function copyToClipboard() {
                var copyText = document.getElementById('sql-code');
                var textArea = document.createElement('textarea');
                textArea.value = copyText.innerText;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                alert('Código copiado para a área de transferência.');
            }
            </script>";
        } else {
            echo "Erro ao criar a tabela 'REG': " . $conn->error . "<br>";
        }
    } else {
        echo "Erro ao criar o banco de dados: " . $conn->error . "<br>";
    }

    $conn->close();
}
?>
