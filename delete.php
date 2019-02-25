<?php

//пропускаем только авторизованного пользователя
session_start();

if(!isset($_SESSION['user_id'])) { 
	header('Location: /login-form.php');
	exit;
}

//получение id записи
$id = $_GET['id'];

//подготовка и выполнение запроса к БД
$pdo = new PDO('mysql:host=localhost;dbname=task_manager', 'homestead', 'secret');
$sql = 'SELECT * from tasks where id=:id';
$statement = $pdo->prepare($sql);
$statement->execute([
	':id'	=>	$id,
]);
$task = $statement->fetch(PDO::FETCH_ASSOC);

//удаляем текущую картинку если существует
if(file_exists('uploads/' . $task['image'])) {
	unlink('uploads/' . $task['image']);
}


//подготовка и выполнение запроса к БД
$sql = 'DELETE from tasks where id=:id';
$statement = $pdo->prepare($sql);
$statement->execute([
	':id'	=>	$id,
]);


header('Location: /index.php');
exit;