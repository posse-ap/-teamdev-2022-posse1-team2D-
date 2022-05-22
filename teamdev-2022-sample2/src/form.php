<?php
session_start();
require(dirname(__FILE__) . "/dbconnect.php");

// 変数の初期化
$page_flag = 0;

if (!empty($_POST['btn_confirm'])) {

  $page_flag = 1;
} elseif (!empty($_POST['btn_submit'])) {

  $page_flag = 2;
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <title>お問い合わせフォーム</title>
  <!-- Bootstrap CSS-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <!-- Bootstrap Icon -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  <!-- 私たちのCSS -->
  <link href="public/css/style.css" rel="stylesheet">
  <!-- Bootstrap JavaScript Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <link type="text/css" rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/cupertino/jquery-ui.min.css" />

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
            <span class="d-inline bg-danger px-2 py-1 text-white circle">1</span>
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
              <a class="h6 nav-link text-dark"  href="agents.php">エージェント一覧</a>
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
    <h2>お問い合わせ手続き</h2>
    <p>必須事項をご記入の上、お問い合わせ内容を入力してください。</p>
    <div class="Form">
      <?php if ($page_flag === 1) : ?>
        <form action="/admin/agent-index.php" method="POST">
          <!-- どの企業をキープしたかのエージェントのid -->
          <!-- foreachでキープした企業の数だけ、以下のinputタグを生成し、valueに、キープしたエージェントidをセット -->
          <!-- <input type="hidden" name="id" value="<?= $result_agent['id']; ?>"> -->
          <div class="Form-Item">
            <p class="Form-Item-Label">
              <span class="Form-Item-Label-Required">必須</span>氏名
            </p>
            <input type="text" name="student_name" class="Form-Item-Input" value="<?=$_POST['student_name']; ?>" required>
          </div>
          <div class="Form-Item">
            <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>大学名</p>
            <input type="text" name="student_university" class="Form-Item-Input" value="<?=$_POST['student_university']; ?>" required>
          </div>
          <div class="Form-Item">
            <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>学部名</p>
            <input type="text" name="student_faculty" class="Form-Item-Input" value="<?=$_POST['student_faculty']; ?>" required>
          </div>
          <div class="Form-Item">
            <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>学科名</p>
            <input type="text" name="student_department" class="Form-Item-Input" value="<?=$_POST['student_department']; ?>" required>
          </div>
          <div class="Form-Item">
            <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>年度卒</p>
            <input type="text" name="student_graduation" class="Form-Item-Input" value="<?=$_POST['student_graduation']; ?>" required>
          </div>
          <div class="Form-Item">
            <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>電話</p>
            <input type="tel" name="student_tel" pattern="\d{2,4}-\d{3,4}-\d{3,4}" class="Form-Item-Input" value="<?=$_POST['student_tel']; ?>" required>
          </div>
          <div class="Form-Item">
            <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>メールアドレス</p>
            <input type="email" name="student_email" class="Form-Item-Input" value="<?=$_POST['student_email']; ?>"  required>
          </div>
          <div class="Form-Item">
            <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>住所</p>
            <input type="text" name="student_address" class="Form-Item-Input" value="<?=$_POST['student_address']; ?>" required>
          </div>
          <div class="Form-Item">
            <p class="Form-Item-Label isMsg"><span class="Form-Item-Label-Required">任意</span>お問い合わせ内容</p>
            <textarea name="student_content" class="Form-Item-Textarea"><?=$_POST['student_content']; ?></textarea>
          </div>
          <p>この内容で送信してよろしいですか？</p>
          <input type="submit" name="btn_back" class="Form-Btn unchecked" onclick=history.back() value="修正する">
          <input type="submit" name="btn_submit" class="Form-Btn unchecked" value="送信">
        </form>

        <!-- サンクスページ -->
      <?php elseif ($page_flag === 2) : ?>
        <div class="card thanks p-3 align-items-center justify-content-center">
          <h3>Thanks!!</h3>
          <p class="second-size">お問い合わせを受け付けました。</p>
          <p>折り返し自動送信メール（確認メール）をお送りさせていただきました。？？？？</p>
          <p> お問い合わせ内容を確認のうえ、回答させて頂きます。 </p>
          <div class="d-flex">
            <p>メールが届いていない場合<br>boozerへのお問い合わせ⇒</p>
            <button onclick="location.href='contact.php'" class="contact-circle  align-items-center justify-content-center mx-2 text-light"><i class="bi bi-envelope"></i></button>
          </div>
          <a href="index.php" class="my-2 link-success"><i class="bi bi-skip-backward-circle"></i>Topページに戻る</a>
        </div>


      <?php else : ?>
<<<<<<< HEAD
        <form action="" method="POST" onsubmit="return check(this)">
=======
        <form action="" method="POST">
          <!-- どの企業をキープしたかのエージェントのid -->
          <!-- foreachでキープした企業の数だけ、以下のinputタグを生成し、valueに、キープしたエージェントidをセット -->
          <!-- <input type="hidden" name="id" value="<?= $result_agent['id']; ?>"> -->
>>>>>>> bc2b9218d857d362e09426d571c0a8f1987aa0a3
          <div class="Form-Item">
            <p class="Form-Item-Label">
              <span class="Form-Item-Label-Required">必須</span>氏名
            </p>
            <input type="text" name="student_name" class="Form-Item-Input" placeholder="例）山田太郎" required>
          </div>
          <div class="Form-Item">
            <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>大学名</p>
            <input type="text" name="student_university" class="Form-Item-Input" placeholder="例）慶應義塾大学" required>
          </div>
          <div class="Form-Item">
            <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>学部名</p>
            <input type="text" name="student_faculty" class="Form-Item-Input" placeholder="例）理工学部" required>
          </div>
          <div class="Form-Item">
            <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>学科名</p>
            <input type="text" name="student_department" class="Form-Item-Input" placeholder="例）管理工学科" required>
          </div>
          <!-- プルダウンメニュー -->
          <div class="Form-Item">
            <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>年度卒</p>
<<<<<<< HEAD
            <select name="student-graduation" id="graduation" class="Form-Item-Input text-secondary" required>
=======
            <select name="student_graduation" id="graduation" class="Form-Item-Input text-secondary" required>
>>>>>>> bc2b9218d857d362e09426d571c0a8f1987aa0a3
              <option value="" class="text-secondary default-word" hidden>選択してください</option>
              <option value="2023" class="text-dark graduation">2023年卒</option>
              <option value="2024" class="text-dark graduation">2024年卒</option>
              <option value="2025" class="text-dark graduation">2025年卒</option>
              <option value="2026" class="text-dark graduation">2026年卒</option>
            </select>
          </div>
          <div class="Form-Item">
            <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>電話</p>
            <input type="tel" name="student_tel" pattern="\d{2,4}-\d{3,4}-\d{3,4}" class="Form-Item-Input" placeholder="例）000-0000-0000" required>
          </div>
          <div class="Form-Item">
            <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>メールアドレス</p>
            <input type="email" name="student_email" class="Form-Item-Input" placeholder="例）example@gmail.com" required>
          </div>
          <div class="Form-Item">
            <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>住所</p>
            <input type="text" name="student_address" class="Form-Item-Input" placeholder="例）東京都世田谷区１丁目" required>
          </div>
          <div class="Form-Item">
            <p class="Form-Item-Label isMsg"><span class="Form-Item-Label-Required">任意</span>お問い合わせ内容</p>
            <textarea name="student_content" class="Form-Item-Textarea"></textarea>
          </div>
          <label class="Form-CheckItem-Label">
            <input type="checkbox" name="" value="" id="JS_CheckItem" class="Form-CheckItem-Label-Input">
            <span class="Form-CheckItem-Label-CheckIcon"></span>
            <span class="Form-CheckItem-Label-SquareIcon"></span>
            <span class="Form-CheckItem-Label-Text"><a href="#">プライバシーポリシー</a>に同意する</span>
          </label>
          <input type="submit" name="btn_confirm" class="Form-Btn send" value="入力内容を確認する">
        </form>
      <?php endif; ?>
    </div>

  </div>

  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <!-- 私たちのJS -->
  <script src="public/js/app.js"></script>
</body>

</html>