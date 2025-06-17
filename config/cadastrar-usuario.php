<?php
//Conecta com banco de dados
include_once('../config/database.php');

//Verifica se os dados vem de um formulario
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    //Coleta e valida os dados de formulario
    //TRIM remove espaços em branco no inicio e no fim
    $nome = trim($_POST['nome']);
    //Valida se é um email valido
    $email = filter_var(trim($_POST['email'], FILTER_VALIDATE_EMAIL));
    $senha = $_POST['senha'];

    //var_dump($_POST);

    //Verifica se os campos estão preenchidos
    if(empty($nome) || empty($email) || empty($senha)){
        echo "Por favor, acha que sou burro? Digite algo!";
        exit();
    }

    //Verifica se email é valido
    if($email === false)
    {
        echo "Por favor, digite um email valido!";
        exit();
    }

    //Criptografa a senha usando o alguritmo padrão
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    //Cadastra no banco de dados
    try{
        //Prepara uma consulta para verificar se já existe um e-mail cadastrado
        $stmt = $conn -> prepare("Select count(*) from usuarios where email = :email");
        //Liga o valor do email ao rotulo email
        $stmt->bindParam(':email',$email);
        //Executa a consulta
        $stmt->execute();
        //Obtém o resultado da contagem e converte para inteiro
        $emailExists = (int)$stmt->fetchColumn();

        //Verifica se existe um email cadastrado
        if($emailExists){
            echo "Usuário já cadastrado!";
            exit();
        }else{
            $stmt = $conn->prepare("INSERT INTO usuarios (nome,email,senha)VALUES (:nome, :email, :senha)");

            $stmt->bindParam(":nome", $nome);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":senha", $senhaHash);

            $stmt->execute();

            header('Location: ../cadastro.html');
            exit();
        }
    } catch (PDOException $e){
        echo "Erro ao cadastrar usuário: ". $e->getMessage();
    }
}