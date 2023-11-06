<?php
session_start();

// Verifica se foi passado o ID do arquivo como parâmetro na URL
if (isset($_SESSION["database_name"])) {
    $localhost = "localhost";
    $username = "root";
    $password = "";
    $databaseName = $_SESSION["database_name"];

    try {
        $dbh = new PDO("mysql:host=$localhost;dbname=$databaseName", $username, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consulta SQL para obter os detalhes do arquivo com base no ID
        $sql = "SELECT file FROM reg WHERE id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(":id", $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();

        // Verifica se o arquivo foi encontrado
        if ($stmt->rowCount() > 0) {
            // Obtém os detalhes do arquivo
            $arquivo = $stmt->fetch(PDO::FETCH_ASSOC);

            $fileContent = $arquivo['file'];
            

            // Configura os cabeçalhos para fazer o download do arquivo
            header('Content-Type: application/octet-stream');
            header("Content-Disposition: attachment; filename=\"$fileContent\"");
           

            // Envia o conteúdo do arquivo para o navegador
            echo $fileContent;
        } else {
            echo "Arquivo não encontrado.";
        }
    } catch (PDOException $e) {
        echo "Erro na conexão: " . $e->getMessage();
    }
} else {
    echo "ID do arquivo não especificado.";
}

?>


