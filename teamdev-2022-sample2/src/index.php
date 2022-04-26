<?php
session_start();
require(dirname(__FILE__) . "/dbconnect.php");

// admin/index.phpでinsert処理等したeventsテーブルから、id, titleを検索
$stmt = $db->query('SELECT id, title FROM events');
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>CRAFT｜TOPページ</title>
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
                    <a href="#" class="keep-star ms-5">
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
        <div class="first-size fw-bold text-center">あなたにぴったりの<br>就活エージェントを見つけよう</div>
        <div class="second-size fw-bold text-center mt-4">
            <p class="d-inline-block">就活エージェントってなに？</p>
            <div class="d-inline-block ms-3 updown">
                <a href="#" class="text-danger" style="text-decoration: none;">
                    <p class="mb-0 third-size">SCROLL</p>
                    <span>
                        <i class="bi bi-arrow-down-circle-fill text-danger first-size"></i>
                    </span>
                </a>
            </div>
        </div>
        <div class="row justify-content-around my-3">
            <div class="col-md-4 mb-4">
                <form action="/result.php">
                    <p class="second-size fw-bold">タグで絞り込む<i class="bi bi-check-all"></i></p>
                    <ul class="tags row">
                        <li class="tag col-6">
                            <input type="checkbox">
                            文系
                        </li>
                        <li class="tag col-6">
                            <input type="checkbox">
                            文系
                        </li>
                        <li class="tag col-6">
                            <input type="checkbox">
                            文系
                        </li>
                        <li class="tag col-6">
                            <input type="checkbox">
                            文系
                        </li>
                        <li class="tag col-6">
                            <input type="checkbox">
                            文系
                        </li>
                        <li class="tag col-6">
                            <input type="checkbox">
                            文系
                        </li>
                        <li class="tag col-6">
                            <input type="checkbox">
                            文系
                        </li>
                        <li class="tag col-6">
                            <input type="checkbox">
                            文系
                        </li>
                    </ul>
                    <button type="submit" class="btn btn-success d-block mx-auto">チェック内容で検索<i class="bi bi-search ms-2"></i></button>
                </form>
            </div>
            <div class="col-md-4">
                <p class="second-size fw-bold">お困りのあなたへ（仮）</p>
                <div class="card recommend-function">
                    <p class="third-size text-light text-center fw-bold">自分にピッタリのエージェントを診断！</p>
                    <div class="row px-3 my-2 g-3">
                        <img class="col-4" src="public/img/feature5.jpg" alt="">
                        <img class="col-4" src="public/img/feature6.jpg" alt="">
                        <img class="col-4" src="public/img/feature7.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>
        <!-- <a href="/admin/index.php">管理者ページ</a> -->
        <!-- <button type="button" class="ps-3 btn btnx--outline"><i class="bi bi-pencil-square"></i>お問い合わせはこちら</button> -->
    </div>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- 私たちのJS -->
    <script src="public/js/app.js"></script>
</body>

</html>