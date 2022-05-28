<?php
session_start();
require('../dbconnect.php');
// 社員テーブルを検索し、emailとpasswordをセットして$employeeに値を返す
// 検索した際に、社員がどのエージェントに属するのかもとれるので、その情報も変数に入れておく
if (!empty($_POST)) {
  $login = $db->prepare('SELECT * FROM employees WHERE email=? AND password=?');
  $login->execute(array(
    $_POST['email'],
    sha1($_POST['password']),
  ));
  $employee = $login->fetch();
  // echo "<pre>";
  // var_dump($employee);
  // echo "</pre>";
  // exit();

  //   $employeeなら、$user['id]をセッションのuser_idとする
  // どのエージェントに属するかのidもセッションで管理。
  // agent-index.phpでは、申込者の情報を表示。applicantsとagentsを紐付け、agentsとemployeesを紐づけて、その情報をselect
  if ($employee) {
    $_SESSION = array();
    $_SESSION['employee_id'] = $employee['id'];
    $_SESSION['agent_id'] = $employee['agent_id'];
    $_SESSION['agent_time'] = time();
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
  <!-- CSS only -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <!-- Bootstrap Icon -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  <!-- CSS for our project -->
  <link href="../public/css/style.css" rel="stylesheet">
  <!-- JavaScript Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body>
  <div class="container-fluid vh-100">
    <div class="" style="margin-top:100px">
      <div class="rounded d-flex justify-content-center">
        <div class="col-md-4 col-sm-12 shadow-lg p-5 bg-light">
          <div class="text-center">
            <h3 class="main-text-color">エージェント：管理者ログイン</h3>
          </div>
          <form action="/admin/agent-login.php" method="POST">
            <div class="p-4">
              <div class="input-group mb-3">
                <span class="input-group-text main-bg-color"><i class="bi bi-person-plus-fill text-white"></i></span>
                <input type="email" name="email" class="form-control" placeholder="メールアドレス" required>
              </div>
              <div class="input-group mb-3">
                <span class="input-group-text  main-bg-color"><i class="bi bi-key-fill text-white"></i></span>
                <input type="password" name="password" class="form-control" placeholder="パスワード" required>
              </div>
              <!-- <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Remember Me
                                    </label>
                                </div> -->
              <div class="text-center">
                <input class="btn  main-bg-color text-light mt-2" type="submit" value="ログイン">
              </div>
              <p class="text-center text-primary mt-5">パスワードをお忘れですか？</p>
              <p class="text-center mt-5">
                <span class="text-primary">弊社とのご提携がお済でない方はお問い合わせください</span>
              </p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>