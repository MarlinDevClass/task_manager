<?php
session_start();

if(!isset($_SESSION['user_id'])) { 
	header('Location: /login-form.php');
	exit;
}

//получение данных из $_POST и $_FILES
$title = $_POST['title'];
$description = $_POST['description'];
$image = $_FILES['image'];



//проверка данных
foreach($_POST as $input) {
    if(empty($input)) {
        include 'errors.php';
        exit;
    }
}
//картинка не загружена
if($image['error'] === 4) {
	$errorMessage = 'Загрузите картинку';
	include 'errors.php';
    exit;	
}


//загрузка картинки в папку uploads
move_uploaded_file($image['tmp_name'], 'uploads/' . $image['name']);

//подготовка и выполнение запроса к БД
$pdo = new PDO('mysql:host=localhost;dbname=task_manager', 'homestead', 'secret');
$sql = "INSERT INTO tasks (title, description, image, user_id) VALUES (:title, :description, :image, :user_id)";
$statement = $pdo->prepare($sql);
$r = $statement->execute([
	":title"	=>	$title,
	":description"	=>	$description,
	":image"	=>	$image['name'],
	":user_id"	=>	$_SESSION['user_id']
]);

header('Location: /index.php');


