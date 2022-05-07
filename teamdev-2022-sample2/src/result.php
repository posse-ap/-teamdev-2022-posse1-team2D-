<?php
session_start();
require(dirname(__FILE__) . "/dbconnect.php");
?>


<?php

    $name = isset($_POST['name'])? htmlspecialchars($_POST['name'], ENT_QUOTES, 'utf-8') : '';
    $tags = isset($_POST['tags'])? htmlspecialchars($_POST['tags'], ENT_QUOTES, 'utf-8') : '';
    // $count = isset($_POST['count'])? htmlspecialchars($_POST['count'], ENT_QUOTES, 'utf-8') : '';

    //配列に入れるには、変数に代入された値が取得できていることが前提なのでif文で空のデータを排除する
   if($name!=''&&$tags!=''){
       $_SESSION['products'][$name]=[
            'tags' => $tags
        ];
   }
   $products = isset($_SESSION['products'])? $_SESSION['products']:[];

  //  if(isset($products)){
  //          foreach($products as $key => $product){
  //              echo $key;      //商品名
  //              echo "<br>";
  //              echo $product['tags']; //商品の金額
  //              echo "<br>";
  //          }
  //      }
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CRAFT 検索結果画面</title>
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
              <a class="h6 nav-link text-dark" href="#">CRAFTを利用した就活の流れ</a>
            </li>
            <li class="nav-item col-md-6">
              <a class="h6 nav-link text-dark" href="#">就活エージェントとは</a>
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
  <!-- コンテンツ -->
  <div class="wrapper">
    <p class="first-size">検索結果一覧（あなたがお探しのエージェントはこちら！！）</p>
    <div class="row">
      <!-- ⚠cardは角丸いのに背景の色は角ばってる⚠ -->
      <div class="col-md-6 my-5 d-flex flex-row">
        <div class="rounded-start col-4 recommend-function d-flex align-items-center justify-content-center px-2">
          <div class="">
            <img src="public/img/feature5.jpg" class="" alt="">
          </div>
        </div>
        <div class="col-4 result-content ps-3">
          <p class="first-size fw-bold">企業名1</p>
          <p class="forth-size">・企業情報</p>
          <p class="forth-size">・企業情報</p>
          <p class="forth-size">・企業情報</p>
        </div>
        <div class="rounded-end col-4 result-content d-flex flex-column justify-content-around align-items-end pe-3">
          <a href="./agent-details/agent1.php" target="_blank" rel="noopener noreferrer" class="link-success"><i class="bi bi-cursor"></i>詳細を見る</a>
          <form action="" method="POST" class="item-form">
            <input type="hidden" name="name" value="リクルート">
            <input type="hidden" name="tags" value="理系">
            <!-- <input type="text" value="1" name="count"> -->
            <button class="keep-btn" type="submit">
              <i class="bi bi-star-fill black-star"></i>
              <i class="bi bi-star white-star"></i>キープする</button>
          </form>

        </div>
      </div>
      <div class="col-md-6 my-5 d-flex flex-row">
        <div class="rounded-start col-4 recommend-function d-flex align-items-center justify-content-center px-2">
          <div class="">
            <img src="public/img/feature5.jpg" class="" alt="">
          </div>
        </div>
        <div class="col-4 result-content ps-3">
          <p class="first-size fw-bold">aaaaa</p>
          <p class="forth-size">rrrrrrrrr</p>
          <p class="forth-size">・企業情報</p>
          <p class="forth-size">・企業情報</p>
        </div>
        <div class="rounded-end col-4 result-content d-flex flex-column justify-content-around align-items-end pe-3">
          <a href="./agent-datails/agent2.php" target="_blank" rel="noopener noreferrer" class="link-success"><i class="bi bi-cursor"></i>詳細を見る</a>
          <form action="" method="POST" class="item-form">
            <input type="hidden" name="name" value="リクナビ">
            <input type="hidden" name="tags" value="エントリーシート">
            <!-- <input type="text" value="1" name="count"> -->
            <button class="keep-btn" type="submit">
              <i class="bi bi-star-fill black-star"></i>
              <i class="bi bi-star white-star"></i>キープする</button>
          </form>

        </div>
      </div>
      <div class="col-md-6 my-5 d-flex flex-row">
        <div class="rounded-start col-4 recommend-function d-flex align-items-center justify-content-center px-2">
          <div class="">
            <img src="public/img/feature5.jpg" class="" alt="">
          </div>
        </div>
        <div class="col-4 result-content ps-3">
          <p class="first-size fw-bold">企業名1</p>
          <p class="forth-size">・企業情報</p>
          <p class="forth-size">・企業情報</p>
          <p class="forth-size">・企業情報</p>
        </div>
        <div class="rounded-end col-4 result-content d-flex flex-column justify-content-around align-items-end pe-3">
          <a href="./agent-details/agent3.php" target="_blank" rel="noopener noreferrer" class="link-success"><i class="bi bi-cursor"></i>詳細を見る</a>
          <button class="keep-btn"><i class="bi bi-star-fill black-star"></i><i class="bi bi-star white-star"></i>キープする</button>
        </div>
      </div>
      <div class="col-md-6 my-5 d-flex flex-row">
        <div class="rounded-start col-4 recommend-function d-flex align-items-center justify-content-center px-2">
          <div class="">
            <img src="public/img/feature5.jpg" class="" alt="">
          </div>
        </div>
        <div class="col-4 result-content ps-3">
          <p class="first-size fw-bold">企業名1</p>
          <p class="forth-size">・企業情報</p>
          <p class="forth-size">・企業情報</p>
          <p class="forth-size">・企業情報</p>
        </div>
        <div class="rounded-end col-4 result-content d-flex flex-column justify-content-around align-items-end pe-3">
          <a href="http://agent-datails/agent1.php" target="_blank" rel="noopener noreferrer" class="link-success"><i class="bi bi-cursor"></i>詳細を見る</a>
          <button class="keep-btn"><i class="bi bi-star-fill black-star"></i><i class="bi bi-star white-star"></i>キープする</button>
        </div>
      </div>
      <div class="col-md-6 my-5 d-flex flex-row">
        <div class="rounded-start col-4 recommend-function d-flex align-items-center justify-content-center px-2">
          <div class="">
            <img src="public/img/feature5.jpg" class="" alt="">
          </div>
        </div>
        <div class="col-4 result-content ps-3">
          <p class="first-size fw-bold">企業名1</p>
          <p class="forth-size">・企業情報</p>
          <p class="forth-size">・企業情報</p>
          <p class="forth-size">・企業情報</p>
        </div>
        <div class="rounded-end col-4 result-content d-flex flex-column justify-content-around align-items-end pe-3">
          <a href="http://agent-datails/agent1.php" target="_blank" rel="noopener noreferrer" class="link-success"><i class="bi bi-cursor"></i>詳細を見る</a>
          <button class="keep-btn"><i class="bi bi-star-fill black-star"></i><i class="bi bi-star white-star"></i>キープする</button>
        </div>
      </div>
    </div>
  </div>
  </div>

  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <!-- 私たちのJS -->
  <script src="public/js/app.js"></script>
</body>

</html>