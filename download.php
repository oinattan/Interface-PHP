<?php
session_start();

if (isset($_SESSION["database_name"])) {
    $localhost = "localhost";
    $username = "root";
    $password = "";
    $databaseName = $_SESSION["database_name"];

    try {
        $dbh = new PDO("mysql:host=$localhost;dbname=$databaseName", $username, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT file FROM reg WHERE id = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(":id", $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $arquivo = $stmt->fetch(PDO::FETCH_ASSOC);

            $fileContent = $arquivo['file'];
        
            header('Content-Type: application/octet-stream');
            header("Content-Disposition: attachment; filename=\"$fileContent\"");
           
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


