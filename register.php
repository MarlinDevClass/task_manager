<?php
//получение данных из $_POST
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];


//проверка данных
foreach($_POST as $input) {
    if(empty($input)) {
        include 'errors.php';
        exit;
    }
}

//подготовка и выполнение запроса к БД
$pdo = new PDO('mysql:host=localhost;dbname=task_manager', 'homestead', 'secret');
$sql = 'SELECT id from users where email=:email';
$statement = $pdo->prepare($sql);
$statement->execute([':email'	=>	$email]);
$user = $statement->fetchColumn();
if($user) {
    $errorMessage = 'Пользователь с таким email уже существует';
    include 'errors.php';
    exit;
}


$sql = 'INSERT INTO users (username, email, password) VALUES (:username, :email, :password)';
$statement = $pdo->prepare($sql);
//password hash
$_POST['password'] = md5($_POST['password']);
$result = $statement->execute($_POST);
if(!$result) {
    $errorMessage = 'Ошибка регистрации';
    include 'errors.php';
    exit;
}

//переадресация на авторизацию(login-form.php)
header('Location: /login-form.php'); exit;