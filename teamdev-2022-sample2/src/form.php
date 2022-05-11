<?php
session_start();
require(dirname(__FILE__) . "/dbconnect.php");

?>
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
              <a class="h6 nav-link text-dark" href="#">エージェント一覧</a>
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
              <a class="h6 nav-link text-dark" href="#">boozerへのお問い合わせ<i class="bi bi-pencil-square"></i></a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
  <div class="wrapper">
    <div class="Form">
      <form action="sample.php" method="POST">
        <div class="Form-Item">
          <p class="Form-Item-Label">
            <span class="Form-Item-Label-Required">必須</span>氏名
          </p>
          <input type="name" class="Form-Item-Input" placeholder="例）山田太郎" required>
        </div>
        <div class="Form-Item">
          <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>大学名</p>
          <input type="text" class="Form-Item-Input" placeholder="例）慶應義塾大学" required>
        </div>
        <div class="Form-Item">
          <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>学部名</p>
          <input type="text" class="Form-Item-Input" placeholder="例）理工学部" required>
        </div>
        <div class="Form-Item">
          <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>学科名</p>
          <input type="text" class="Form-Item-Input" placeholder="例）管理工学科" required>
        </div>
        <!-- プルダウンメニュー -->
        <div class="Form-Item">
          <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>年度卒</p>
          <!-- <input type="text" data-options="2022,2023,2024,2025,2026" class="Form-Item-Input" placeholder="例）23年度卒" required> -->
          <select name="" id="graduation"  class="Form-Item-Input text-secondary">
            <option value="none" class="text-secondary default-word">選択してください</option>
            <option value="2022" class="text-dark graduation">2022年卒</option>
            <option value="2023" class="text-dark graduation">2023年卒</option>
            <option value="2024" class="text-dark graduation">2024年卒</option>
            <option value="2025" class="text-dark graduation">2025年卒</option>
            <option value="2026" class="text-dark graduation">2026年卒</option>
          </select>
        </div>
        <div class="Form-Item">
          <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>電話</p>
          <input type="tel" pattern="\d{2,4}-\d{3,4}-\d{3,4}" class="Form-Item-Input" placeholder="例）000-0000-0000" required>
        </div>
        <div class="Form-Item">
          <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>メールアドレス</p>
          <input type="email" class="Form-Item-Input" placeholder="例）example@gmail.com" required>
        </div>
        <div class="Form-Item">
          <p class="Form-Item-Label"><span class="Form-Item-Label-Required">必須</span>住所</p>
          <input type="text" class="Form-Item-Input" placeholder="例）東京都世田谷区１丁目" required>
        </div>
        <div class="Form-Item">
          <p class="Form-Item-Label isMsg"><span class="Form-Item-Label-Required">任意</span>お問い合わせ内容</p>
          <textarea class="Form-Item-Textarea"></textarea>
        </div>
        <label class="Form-CheckItem-Label">
          <input type="checkbox" name="" value="" id="JS_CheckItem" class="Form-CheckItem-Label-Input">
          <span class="Form-CheckItem-Label-CheckIcon"></span>
          <span class="Form-CheckItem-Label-SquareIcon"></span>
          <span class="Form-CheckItem-Label-Text"><a href="#">プライバシーポリシー</a>に同意する</span>
        </label>
        <input type="submit" class="Form-Btn send" value="送信する">
      </form>
    </div>
    <div class="Thanks">


    </div>

  </div>

  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <!-- 私たちのJS -->
  <script src="public/js/app.js"></script>
</body>

</html>