<?php
require(dirname(__FILE__) . "/dbconnect.php");
$keep_email = isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'utf-8') : '';
$keep_name = isset($_POST['name']) ? htmlspecialchars($_POST['name'], ENT_QUOTES, 'utf-8') : '';
header('Location: http://' . $_SERVER['HTTP_HOST'] . '/result.php');
exit();
