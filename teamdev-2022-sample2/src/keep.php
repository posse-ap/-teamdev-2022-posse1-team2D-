<?php
session_start();
require(dirname(__FILE__) . "/dbconnect.php");

$agents = isset($_SESSION['agents']) ? $_SESSION['agents'] : [];



// $agents = isset($_SESSION['agents'])? $_SESSION['agents']:[];
?>
<?php
// $keep_count = 0;
// $keep_count = isset($_POST['count'])? htmlspecialchars($_POST['count'], ENT_QUOTES, 'utf-8') : '';
// $keep_count = intval($keep_count);

// 他のページからカウントを引き継ぐ
$keep_count = $_SESSION['keep_count'];
$keep_count = intval($keep_count);

// もし削除ボタンが押されたら
if (isset($_POST['delete_name'])) {
  $delete_name = $_POST['delete_name'];
  // 該当のセッションをunset
  unset($_SESSION['agents'][$delete_name]);
  // カウントの初期化
  $keep_count = 0;
  if (!empty($_SESSION['agents'])) {
    // 残されたセッションの数だけ、カウントをプラスし、それをセッションとして持つ
    foreach ($_SESSION['agents'] as $post_name) {
      $keep_count = $keep_count + 1;
      $_SESSION['keep_count'] = $keep_count;
    }
  } elseif (count($_SESSION['agents']) == 0) {
    $keep_count = 0;
    $_SESSION['keep_count'] = $keep_count;
  }
  header('Location: http://' . $_SERVER['HTTP_HOST'] . '/keep.php');
}
?>
<?php
if ($agent['keep_id'] != '' && $agent['keep_email'] != '') {
  $_SESSION['emails'][$agent['keep_id']] = [
    'keep_email' => $agent['keep_email'],
  ];
}
?>

<?php $emails = array(); ?>
<?php $names = array(); ?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <title>キープ企業確認画面</title>
  <!-- Bootstrap CSS-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <!-- Bootstrap Icon -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  <!-- 私たちのCSS -->
  <link href="public/css/style.css" rel="stylesheet">
  <!-- Bootstrap JavaScript Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body>
  <!-- ヘッダー -->
  <header>
    <!-- ナヴィゲーション -->
    <nav class="navbar navbar-dark fixed-top py-1 px-4" id="header">
      <!-- container-fluid・・・横幅はどのデバイスでも画面幅全体 -->
      <div class="container-fluid">

        <a class="navbar-brand fw-bold me-md-5 text-light" href="#">
          <h1 class="mb-0">CRAFT</h1>
          <div class="h6">by 就活.com</div>
        </a>

        <div class="float-end">
          <!-- 法人ページ（ログインしている場合は管理画面、していない場合はログイン画面に遷移 -->
          <a href="/admin/index.php" class="h5 text-light d-none d-md-inline corporation-link">法人の方へ</a>
          <!-- キープマーク -->
          <a href="keep.php" class="keep-star ms-5">
            <i class="bi bi-star text-light" style="font-size: 1.6rem;"></i>
            <span class="d-inline bg-danger px-2 py-1 text-white circle"><?php echo $keep_count; ?></span>
          </a>
          <!-- ハンバーガーメニューボタン -->
          <button class="navbar-toggler ms-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>
        <!-- ハンバーガーメニュー内部 -->
        <div class="collapse navbar-collapse bg-light navbar-expand-lg" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 ps-3 py-2 mb-lg-0 row">
            <li class="nav-item col-md-6">
              <a class="h6 nav-link active text-dark" aria-current="page" href="index.php">トップページ</a>
            </li>
            <li class="nav-item col-md-6">
              <a class="h6 nav-link text-dark" href="agents.php">エージェント一覧</a>
            </li>
            <li class="nav-item col-md-6">
              <a class="h6 nav-link text-dark" href="index.php#CRAFTSec">CRAFTを利用した就活の流れ</a>
            </li>
            <li class="nav-item col-md-6">
              <a class="h6 nav-link text-dark" href="index.php#jobHuntingSec">就活エージェントとは</a>
            </li>
            <li class="nav-item col-md-6">
              <a class="h6 nav-link text-dark" href="#">よくあるご質問</a>
            </li>
            <li class="nav-item col-md-6">
              <a class="h6 nav-link text-dark" href="contact.php">boozerへのお問い合わせ<i class="bi bi-pencil-square"></i></a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
  <div class="wrapper">
    <p class="h2 fw-bold text-center">キープ企業一覧</p>
    <div class="row">
      <?php foreach ($agents as $name => $agent) : ?>
        <div class="col-md-6 my-5 d-flex flex-row">
          <div class="rounded-start col-4 recommend-function d-flex align-items-center justify-content-center px-2">
            <div class="">
              <img src="public/images/<?php echo $agent['keep_logo']; ?>" class="" alt="">
            </div>
          </div>
          <div class="col-4 result-content ps-3">
            <p class="first-size fw-bold"><?php echo $name; ?></p>
            <p class="forth-size mb-0"><i class="bi bi-tags-fill"></i>タグ</p>
            <p class="forth-size"><?php foreach ($agent['keep_tags'] as $key => $agent_keep_tag) : ?><?php echo $agent_keep_tag . " "; ?><?php endforeach; ?></p>
            <div class="mb-2">
              <a href="<?php echo $agent['keep_site']; ?>" class="forth-size" target="_blank" rel="noopener noreferrer">・公式サイト</a>
            </div>
          </div>
          <div class="rounded-end col-4 result-content d-flex flex-column justify-content-around align-items-end pe-3">
            <a href="<?= $agent['keep_detail']; ?>" target="_blank" rel="noopener noreferrer" class="link-success"><i class="bi bi-cursor"></i>詳細へ</a>
            <form action="keep.php" method="POST">
              <input type="hidden" name="id" value="<?= $result_agent['id']; ?>">
              <input type="hidden" name="email" value="<?= $result_agent['email']; ?>">
              <input type="hidden" name="name" value="<?= $result_agent['agent_name']; ?>">
              <input type="hidden" name="delete_name" value="<?= $name; ?>">
              <button class="delete-btn" type="submit"><i class="bi bi-star-fill black-star"></i>削除</button>
            </form>
          </div>
        </div>
        <p class=""><?php echo $agent['keep_id']; ?></p>
        <p class=""><?php echo $agent['keep_email']; ?></p>
        <p class=""><?php echo $name; ?></p>
        <?php $email = $agent['keep_email']; ?>
        <!-- <?php var_dump($email); ?> -->

        <?php array_push($emails, $email);
        array_push($names, $name);
        // var_dump($emails);
        $_SESSION['emails'] = $emails;
        $_SESSION['names'] = $names;
        // var_dump($_SESSION['emails']);
        ?>


      <?php endforeach; ?>
      <!-- <?php echo $emails; ?> -->

    </div>
    <div class="d-flex flex-column align-items-center">
      <a class="btn btn-danger" href="form.php"><i class="bi bi-pencil-square"></i>フォームでお問い合わせ</a>
      <a class="btn continue-btn my-5 text-light" href="index.php"><i class="bi bi-arrow-left-circle"></i>企業探しを続ける</a>
    </div>
  </div>
</body>