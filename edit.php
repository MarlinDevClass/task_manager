<?php
//пропускаем только авторизованного пользователя
session_start();

if(!isset($_SESSION['user_id'])) { 
	header('Location: /login-form.php');
	exit;
}

//получение данных из $_POST и $_FILES
$title = $_POST['title'];
$description = $_POST['description'];
$image = $_FILES['image'];
$id = $_GET['id'];

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

//загрузка картинки в папку uploads
move_uploaded_file($image['tmp_name'], 'uploads/' . $image['name']);

//подготовка и выполнение запроса к БД
$sql = "UPDATE tasks SET title=:title, description=:description, image=:image WHERE id=:id";
$statement = $pdo->prepare($sql);
$r = $statement->execute([
	":title"	=>	$title,
	":description"	=>	$description,
	":image"	=>	$image['name'],
	":id"	=>	$id
]);

header('Location: /index.php');

