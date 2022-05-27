<?php
session_start();
$emails = isset($_SESSION['emails']) ? $_SESSION['emails'] : [];
// var_dump($emails);
// exit();

$from = 'from@example.com';
// foreach($emails )
$to   = 'miyamiu19642002@gmail.com,example@gmail.com';
$subject = 'テストメール';
$body = 'メールの送信テストです。';

$ret = mb_send_mail($to, $subject, $body, "From: {$from} \r\n");
var_dump($ret);