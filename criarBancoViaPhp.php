<?php
$host = 'localhost';
$dbname = 'cadastronoticias';
$user = 'root';
$pass = '';

try {
    $conn = new PDO("mysql:host=$host", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se o banco de dados existe
    $stmt = $conn->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'");
    $dbExists = $stmt->fetchColumn();

    if (!$dbExists) {
        // Cria o banco de dados se não existe
        $conn->exec("CREATE DATABASE $dbname");
        echo "Banco de dados $dbname criado com sucesso.";
    }

    // Conecta ao banco de dados especificado
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se a tabela de usuários existe
    $stmt = $conn->query("SHOW TABLES LIKE 'usuarios'");
    $tableExists = $stmt->rowCount() > 0;

    if (!$tableExists) {
        // Cria a tabela de usuários se não existe
        $conn->exec("
            CREATE TABLE usuarios (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(100),
                email VARCHAR(100),
                senha VARCHAR(255)
            )
        ");
        echo "Tabela de usuários criada com sucesso.";
    }

    // Verifica se a tabela de notícias existe
    $stmt = $conn->query("SHOW TABLES LIKE 'noticias'");
    $tableExists = $stmt->rowCount() > 0;

    if (!$tableExists) {
        // Cria a tabela de notícias se não existe
        $conn->exec("
            CREATE TABLE noticias (
                id INT AUTO_INCREMENT PRIMARY KEY,
                titulo VARCHAR(255),
                noticia TEXT,
                imagem VARCHAR(255)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
        echo "Tabela de notícias criada com sucesso.";
    }
} catch (PDOException $e) {
    echo 'Erro na conexão com o banco de dados: ' . $e->getMessage();
}
?>
