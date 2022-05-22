<?php
session_start();
require('../dbconnect.php');
// 社員テーブルを検索し、emailとpasswordをセットして$employeeに値を返す
// 検索した際に、社員がどのエージェントに属するのかもとれるので、その情報も変数に入れておく
if (!empty($_POST)) {
  $login = $db->prepare('SELECT * FROM users WHERE email=? AND password=?');
//   $login = $db->prepare('SELECT * FROM employees WHERE email=? AND password=?');
  $login->execute(array(
    $_POST['email'],
    sha1($_POST['password'])
  ));
  $user = $login->fetch();
//   $employee = $login->fetch();

//   $employeeなら、$user['id]をセッションのuser_idとする
// どのエージェントに属するかのidもセッションで管理。
// agent-index.phpでは、申込者の情報を表示。applicantsとagentsを紐付け、agentsとemployeesを紐づけて、その情報をselect
  if ($user) {
    $_SESSION = array();
    $_SESSION['user_id'] = $user['id'];
    // $_SESSION['agent_id'] = $employee['agent_id']みたいにする
    $_SESSION['time'] = time();
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/admin/agent-index.php');
    exit();
  } else {
    $error = 'fail';
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>エージェント：管理者ログイン</title>
</head>

<body>
  <div>
    <h1>エージェント：管理者ログイン</h1>
    <form action="/admin/agent-login.php" method="POST">
      <input type="email" name="email" required>
      <input type="password" required name="password">
      <input type="submit" value="ログイン">
    </form>
  </div>
</body>

</html>