<?php
session_start();
require(dirname(__FILE__) . "/dbconnect.php");

// admin/index.phpでinsert処理等したeventsテーブルから、id, titleを検索
// $stmt = $db->query('SELECT id, title FROM events');
// $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($_SESSION['keep_count'])) {
    $keep_count = 0;
    $_SESSION['keep_count'] = $keep_count;
} else {
    $keep_count = $_SESSION['keep_count'];
}

if (isset($_POST['remove'])) {
    unset($_SESSION['agents']);
    $keep_count = 0;
    $_SESSION['keep_count'] = $keep_count;
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>TOPページ｜CRAFT</title>
    <!-- Bootstrap CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <!-- 私たちのCSS -->
    <link href="public/css/style.css" rel="stylesheet">
    <!--==============レイアウトを制御する独自のCSSを読み込み===============-->
    <link rel="stylesheet" type="text/css" href="http://coco-factory.jp/ugokuweb/wp-content/themes/ugokuweb/data/move02/8-9/css/reset.css">
    <link rel="stylesheet" type="text/css" href="http://coco-factory.jp/ugokuweb/wp-content/themes/ugokuweb/data/move02/8-9/css/8-9.css">
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
                <a class="navbar-brand fw-bold me-md-5 text-light" href="index.php#">
                    <h1 class="mb-0">CRAFT</h1>
                    <div class="h6">by 就活.com</div>
                </a>

                <div class="float-end">
                    <!-- 法人ページ（ログインしている場合は管理画面、していない場合はログイン画面に遷移 -->
                    <a href="/admin/agent-index.php" class="h5 text-light d-none d-md-inline corporation-link me-5">エージェントの方へ</a>
                    <a href="/admin/index.php" class="h5 text-light d-none d-md-inline corporation-link">CRAFT担当者へ</a>
                    <!-- キープマーク -->
                    <a href="keep.php" class="keep-star ms-5" style="position: relative;">
                        <i class="bi bi-star text-light" style="font-size: 1.6rem;"></i>
                        <span class="d-inline bg-danger px-2 py-1 text-white circle"><?= $keep_count; ?></span>
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
                            <a class="h6 nav-link text-dark" href="contact.php">boozerへのお問い合わせ<i class="bi bi-pencil-square"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <!-- コンテンツ -->
    <div class="">
        <div class="hero wrapper">
            <div class="first-size fw-bold text-center blurTrigger"><span class="outline">あなたにぴったりの</span><br><span class="outline">就活エージェントを見つけよう！</span></div>
            <main class="row justify-content-around my-3">
                <!-- タグで絞り込むカード -->
                <section class="col-md-4 mt-md-4 mb-4 bg-light bg-body rounded">
                    <form action="/result.php" method="POST">
                        <p class="second-size fw-bold text-center my-2 mt-md-2">タグで絞り込む<i class="bi bi-check-all"></i></p>
                        <ul class="tags row">
                            <li class="tag col-6 fw-bold text-center pt-2">求人エリア</li>
                            <?php //tagを取得する
                            $stmt = $db->query('SELECT id, name FROM tags');
                            $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <?php for ($i = 0; $i <= 2; $i++) : ?>
                                <label for="<?= $tags[$i]["id"]; ?>" class="tag col-6 pt-2">
                                    <li>
                                        <input id="<?= $tags[$i]["id"]; ?>" type="checkbox" name="tag[]" value="<?= $tags[$i]["id"]; ?>" class="q2 form-check-input me-1" id="flexCheckDefault"><?= $tags[$i]["name"]; ?>
                                    </li>
                                </label>
                            <?php endfor; ?>
                            <li class="tag col-6 fw-bold text-center pt-2">紹介傾向</li>
                            <?php for ($i = 3; $i <= 5; $i++) : ?>
                                <label for="<?= $tags[$i]["id"]; ?>" class="tag col-6 pt-2">
                                    <li>
                                        <input id="<?= $tags[$i]["id"]; ?>" type="checkbox" name="tag[]" value="<?= $tags[$i]["id"]; ?>" class="q2 form-check-input me-1" id="flexCheckDefault"><?= $tags[$i]["name"]; ?>
                                    </li>
                                </label>
                            <?php endfor; ?>
                            <!-- <?php foreach ($tags as $key => $tag) : ?>
                                <label for="<?= $tag["id"]; ?>" class="tag col-6 pt-2">
                                    <li>
                                        <input id="<?= $tag["id"]; ?>" type="checkbox" name="tag[]" value="<?= $tag["id"]; ?>" class="q2 form-check-input me-1" id="flexCheckDefault"><?= $tag["name"]; ?>
                                    </li>
                                </label>
                            <?php endforeach; ?> -->
                        </ul>
                        <ul class="second_tags row">
                            <li class="tag col-6 fw-bold text-center pt-2">学問分野</li>
                            <?php //tagを取得する
                            $stmt = $db->query('SELECT id, name FROM tags');
                            $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <?php for ($i = 6; $i <= 7; $i++) : ?>
                                <label for="<?= $tags[$i]["id"]; ?>" class="tag col-6 pt-2">
                                    <li>
                                        <input id="<?= $tags[$i]["id"]; ?>" type="checkbox" name="tag[]" value="<?= $tags[$i]["id"]; ?>" class="q2 form-check-input me-1" id="flexCheckDefault"><?= $tags[$i]["name"]; ?>
                                    </li>
                                </label>
                            <?php endfor; ?>
                            <li class="tag col-6 fw-bold text-center pt-2">面談</li>
                            <?php for ($i = 8; $i <= 9; $i++) : ?>
                                <label for="<?= $tags[$i]["id"]; ?>" class="tag col-6 pt-2">
                                    <li>
                                        <input id="<?= $tags[$i]["id"]; ?>" type="checkbox" name="tag[]" value="<?= $tags[$i]["id"]; ?>" class="q2 form-check-input me-1" id="flexCheckDefault"><?= $tags[$i]["name"]; ?>
                                    </li>
                                </label>
                            <?php endfor; ?>
                            <!-- <?php foreach ($tags as $key => $tag) : ?>
                                <label for="<?= $tag["id"]; ?>" class="tag col-6 pt-2">
                                    <li>
                                        <input id="<?= $tag["id"]; ?>" type="checkbox" name="tag[]" value="<?= $tag["id"]; ?>" class="q2 form-check-input me-1" id="flexCheckDefault"><?= $tag["name"]; ?>
                                    </li>
                                </label>
                            <?php endforeach; ?> -->
                        </ul>
                        <button type="submit" class="search-agents mb-3 btn d-block mx-auto text-light">チェック内容で検索<i class="bi bi-search ms-2"></i></button>
                    </form>
                </section>
                <!-- 就活エージェント説明カード -->
                <section class="col-md-4 mt-md-4 mb-4 bg-light bg-body rounded">
                    <div class="text-center">
                        <p id="jobHuntingSec" class="second-size fw-bold my-2 mt-md-2">就活エージェントとは<i class="ps-2 bi bi-question-diamond"></i></p>
                        <div class="mx-auto">
                            <p class="third-size text-start lh-base m-0">
                                自分で企業を探しエントリーするという一般のスタイルとは異なり、プロフェッショナルなコンサルタントの視点から、強みの引き出しや適職の紹介を受けることができるサービスです。
                                応募書類や面接などに関する対策を、あなたの専任コンサルタントともに考えながら、
                                “あなたにとってより望ましい就職”の実現に努めています。
                            </p>
                        </div>
                        <!-- Button trigger modal -->
                        <button type="button" class="search-details btn mt-2 mb-3 text-light" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            クリックで詳細表示
                            <i class="bi bi-arrow-left-circle"></i>
                        </button>

                        <!-- Modal -->
                        <div class="modal fade pe-0" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">就活エージェントを活用するメリット</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="d-flex" style="border-bottom: 1px solid #dee2e6;">
                                            <img src="public/images/resume.png" class="w-50" alt="">
                                            <img src="public/images/job hunting.png" class="w-50" alt="">
                                        </div>
                                        <section class="p-2 mb-2" style="background-color: #f5f5f5;">
                                            <p class="third-size fw-bold text-start mt-0">1. 就職情報サイトで出会えない、優良企業と出会えます。</p>
                                            <div class="forth-size fw-bold text-start">
                                                就職情報サイト に掲載されていない非公開求人も含め、徹底審査・取材の結果、弊社がホワイト企業と認定した企業を厳選しています。
                                            </div>
                                        </section>
                                        <section class="p-2 mb-2" style="background-color: #f5f5f5;">
                                            <p class="third-size fw-bold text-start mt-0">2. 自分ひとりでは気づきづらい情報を得られます。</p>
                                            <div class="forth-size fw-bold text-start">
                                                皆さんの隠れた適性や価値観、インターネット上では手に入れることが困難なリアルな採用情報も、個別カウンセリングを通してお伝えしています。
                                            </div>
                                        </section>
                                        <section class="p-2 mb-2" style="background-color: #f5f5f5;">
                                            <p class="third-size fw-bold text-start mt-0">3. 履歴書1枚で、何社でも企業にエントリ－ができます。</p>
                                            <div class="forth-size fw-bold text-start">
                                                効率的に企業と接触する機会を増やせ、自分自身の可能性を広げられます。
                                            </div>
                                        </section>
                                    </div>
                                    <div class="modal-footer">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
        <div class="wrapper pt-0">
            <p id="CRAFTSec" class="second-size fw-bold text-center p-1 text-light use-craft">CRAFTを利用した就活の流れ</p>
            <div class="d-flex flex-wrap align-items-center justify-content-center my-1 py-1 my-md-5 bg-light">
                <div class="text-center col-md-4 col-6">
                    <p class="third-size">①クラフト使用</p>
                    <img src="public/images/logo_4.png" alt="" class="w-25">
                </div>
                <div class="text-center col-md-4 col-6">
                    <p class="third-size">②エージェント比較</p>
                    <img src="public/images/logo_2.png" alt="" class="w-25">
                </div>
                <div class="text-center col-md-4 col-6">
                    <p class="third-size">③エージェントに問い合わせ</p>
                    <img src="public/images/logo_3.png" alt="" class="w-25">
                </div>
                <div class="text-center col-md-4 col-6">
                    <p class="third-size">④エージェントを登録</p>
                    <img src="public/images/logo_1.png" alt="" class="w-25">
                </div>
                <div class="text-center col-md-4 col-6">
                    <p class="third-size">⑤企業紹介</p>
                    <img src="public/images/logo_5.png" alt="" class="w-25">
                </div>
                <div class="text-center col-md-4 col-6">
                    <p class="third-size">⑥内定</p>
                    <img src="public/images/logo_6.png" alt="" class="w-25">
                </div>
            </div>
            <p class="second-size fw-bold text-center p-1 text-light use-craft">CRAFTを利用するメリット</p>
            <div class="d-flex flex-wrap align-items-center justify-content-center my-1 py-1 my-md-5 bg-light">
                <div class="text-center col-md-5 col-6 px-2">
                    <p class="third-size">自分の希望に合った<br>エージェントが絞り込める</p>
                    <div class="image-trim">
                    <img src="public/images/front1.png" alt="" class="img-responsive" >
                </div>
                </div>
                <div class="text-center col-md-5 col-6 px-2">
                    <p class="third-size">就活エージェントを<br>中立的な立場で比較できる</p>
                    <div class="image-trim">
                    <img src="public/images/front3.png" alt=""  class="img-responsive" >
                    </div>
                </div>
                <div class="text-center col-md-5 col-6 px-2">
                    <p class="third-size">一度に複数のエージェントに<br>お問い合わせできる</p>
                    <div class="image-trim">
                    <img src="public/images/front4.png" alt=""class="img-responsive" >
                    </div>
                </div>
                <div class="text-center col-md-5 col-6 px-2">
                    <p class="third-size">自分に適正な<br>エージェントを発見できる</p>
                    <div class="image-trim">
                    <img src="public/images/front2.png" alt=""  class="img-responsive" >
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- フッター -->
    <footer class="mt-3">
        <div id="footer">
            <div class="text-center">
                <a class="h1 mb-0 me-md-5 text-light" href="#">CRAFT</a>
            </div>
            <div class="text-center">
                <a class="h6 me-md-5 text-light" href="#">by 就活.com</a>
            </div>
            <div class="footer-nav">
                <ul class="ps-0 my-0">
                    <li>
                        <a class="text-light" href="index.php">トップページ</a>
                    </li>
                    <li>
                        <a class="text-light" href="agents.php">エージェント一覧</a>
                    </li>
                    <li>
                        <a class="text-light" href="index.php#CRAFTSec">CRAFTを利用した就活の流れ</a>
                    </li>
                    <li>
                        <a class="text-light" href="index.php#jobHuntingSec">就活エージェントとは</a>
                    </li>
                    <li>
                        <a class="text-light" href="contact.php">boozerへのお問い合わせ</a>
                    </li>
                </ul>
            </div>
        </div>
    </footer>
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- 私たちのJS -->
    <script src="public/js/app.js"></script>
</body>

</html>