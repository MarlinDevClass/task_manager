<?php

//пропускаем только авторизованного пользователя
session_start();

if(!isset($_SESSION['user_id'])) { 
	header('Location: /login-form.php');
	exit;
}

//удаляем данные из сессии
unset($_SESSION['user_id']);
unset($_SESSION['email']);

header('Location: /login-form.php');
exit;