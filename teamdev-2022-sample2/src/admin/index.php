<?php
session_start();
require('../dbconnect.php');
// if (isset($_SESSION['user_id']) && $_SESSION['time'] + 60 * 60 * 24 > time()) {
//     $_SESSION['time'] = time();

//     if (!empty($_POST)) {
//         // echo __LINE__ . PHP_EOL;
//         $stmt = $db->prepare('INSERT INTO events SET title=?');
//         $stmt->execute(array(
//             $_POST['title']
//         ));

//         header('Location: http://' . $_SERVER['HTTP_HOST'] . '/admin/index.php');
//         exit();
//     }
// } else {
//     header('Location: http://' . $_SERVER['HTTP_HOST'] . '/admin/login.php');
//     exit();
// }
// // admin/index.phpでinsert処理等したeventsテーブルから、id, titleを検索
// $stmt = $db->query('SELECT id, title FROM events');
// $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ---------------------エージェント登録の処理-------------------------------------------------------------------------------------
if (isset($_SESSION['user_id']) && $_SESSION['time'] + 60 * 60 * 24 > time()) {
    $_SESSION['time'] = time();

    // エージェント登録のフォームデータを受け取り、データベースにいれる
    if (!empty($_POST)) {
        try {
            // 送信された値を取得
            $agent_name = $_POST['agent_name'];
            $agent_url = $_POST['agent_url'];
            $representative = $_POST['representative'];
            $contractor = $_POST['contractor'];
            $department = $_POST['department'];
            $email = $_POST['email'];
            $phone_number = $_POST['phone_number'];
            $address = $_POST['address'];
            $post_period = $_POST['post_period'];

            // INSERT文を変数に格納。プレスホルダーは、値を入れるための空箱
            $sql = "INSERT INTO agents (agent_name, agent_url, representative, contractor, department, email, phone_number, address, post_period) VALUES 
        (:agent_name, :agent_url, :representative, :contractor, :department, :email, :phone_number, :address, :post_period)";
            $stmt = $db->prepare($sql); //挿入する値は空のまま、SQL実行の準備をする

            //送信された値を、データベースのカラムに結びつける

            //  
            $stmt->bindValue(":agent_name",  $agent_name, PDO::PARAM_STR);
            // 
            $stmt->bindValue(":agent_url",  $agent_url, PDO::PARAM_STR);
            // 
            $stmt->bindValue(":representative",  $representative, PDO::PARAM_STR);
            // 
            $stmt->bindValue(":contractor",  $contractor, PDO::PARAM_STR);
            // 
            $stmt->bindValue(":department",  $department, PDO::PARAM_STR);
            // 
            $stmt->bindValue(":email",  $email, PDO::PARAM_STR);
            // 
            $stmt->bindValue(":phone_number",  $phone_number, PDO::PARAM_STR);
            // 
            $stmt->bindValue(":address",  $address, PDO::PARAM_STR);
            // 
            $stmt->bindValue(":post_period", date("Y-m-d", strtotime($post_period)), PDO::PARAM_STR);
            // 
            $stmt->execute();
            header('Location: http://' . $_SERVER['HTTP_HOST'] . '/admin/index.php');
            exit();
        } catch (PDOException $e) {
            exit('データベースに接続できませんでした。' . $e->getMessage());
        }
    }
} else {
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/admin/login.php');
    exit();
}
// ------------------エージェント登録の処理(fin)---------------------------------------

// ------------------データベースの件数に対応させた動的ページネーション-----------------------------------------

//$count_sqlはデータの件数取得に使うための変数。
$count_sql = 'SELECT COUNT(*) as cnt FROM agents';

//ページ数を取得する。GETでページが渡ってこなかった時(最初のページ)のときは$pageに１を格納する。
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

//最大ページ数を取得する。
//10件ずつ表示させているので、$count['cnt']に入っている件数を10で割って小数点は切りあげると最大ページ数になる。
$counts = $db->query($count_sql);
$count = $counts->fetch(PDO::FETCH_ASSOC);
$max_page = ceil($count['cnt'] / 10);

if ($page == 1 || $page == $max_page) {
    $range = 4;
} elseif ($page == 2 || $page == $max_page - 1) {
    $range = 3;
} else {
    $range = 2;
}

$from_record = ($page - 1) * 10 + 1;

if ($page == $max_page && $count['cnt'] % 10 !== 0) {
    $to_record = ($page - 1) * 10 + $count['cnt'] % 10;
} else {
    $to_record = $page * 10;
}
// ------------------動的ページネーション(fin)-----------------------------------------

// -----------------ページ切り替えごとに10件データ取得------------------------------------------------------------
$page_change_record = $from_record - 1;
$stmt = $db->prepare('SELECT id, agent_name, agent_url, representative, contractor, department, email, phone_number, address, post_period, deleted_at FROM agents WHERE deleted_at = 0 LIMIT ?, 10');
$stmt->bindParam(1, $page_change_record, PDO::PARAM_INT);
$stmt->execute();
$agents = $stmt->fetchAll(PDO::FETCH_ASSOC);
// -------------------------------------------------------------------------------------------------------------
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ログイン</title>
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
    <!-- ヘッダー -->
    <header>
        <!-- ナヴィゲーション -->
        <nav class="navbar navbar-dark fixed-top py-1 px-4" id="header">
            <!-- container-fluid・・・横幅はどのデバイスでも画面幅全体 -->
            <div class="container-fluid">

                <a class="navbar-brand fw-bold me-md-5 text-light" href="/index.php">
                    <h1 class="mb-0">CRAFT</h1>
                    <div class="h6">by 就活.com</div>
                </a>

                <h1 class="ms-3 text-light">エージェント情報管理画面</h1>

                <div class="float-end h5 text-light">
                    <div class="d-inline mx-5">○○様</div>
                    <a>ログアウト</a>
                </div>

            </div>
        </nav>
    </header>
    <!-- コンテンツ -->
    <div class="admin-wrapper">
        <!-- エージェント登録 -->
        <div class="content">
            <a class="js-modal-open btn btn-lg btn-success" href="">エージェントの登録</a>
        </div>
        <!--modal-->
        <div class="modal js-modal">
            <div class="modal__bg js-modal-close"></div>
            <!--modal__inner-->
            <div class="modal__content">
                <form action="/admin/index.php" method="POST" class="ms-3">
                    社名：<input class="d-block" type="text" name="agent_name" required>
                    会社URL：<input class="d-block" type="url" name="agent_url" placeholder="http://example.jp" required>
                    代表者名：<input class="d-block" type="text" name="representative" required>
                    契約担当者名：<input class="d-block" type="text" name="contractor" required>
                    部署：<input class="d-block" type="text" name="department" required>
                    メールアドレス：<input class="d-block" type="email" name="email" placeholder="info@example.com" required>
                    電話番号：<input class="d-block" type="tel" name="phone_number" placeholder="電話番号" required>
                    住所：<input class="d-block" type="text" name="address" required>
                    掲載期間：<input class="d-block" type="date" name="post_period" required>
                    <input class="d-block" type="submit" value="登録する">
                </form>
                <a class="js-modal-close" href="">閉じる</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mb-lg-0">
                <div class="card">
                    <h5 class="card-header">契約エージェント一覧</h5>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">社名</th>
                                        <th scope="col">会社URL</th>
                                        <th scope="col">代表者名</th>
                                        <th scope="col">契約担当者名</th>
                                        <th scope="col">部署</th>
                                        <th scope="col">メールアドレス</th>
                                        <th scope="col">電話番号</th>
                                        <th scope="col">住所</th>
                                        <th scope="col">掲載期間</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($agents as $key => $agent) : ?>
                                        <tr>
                                            <th scope="row"><?= $agent["id"]; ?></th>
                                            <td><?= $agent["agent_name"]; ?></td>
                                            <td><?= $agent["agent_url"]; ?></td>
                                            <td><?= $agent["representative"]; ?></td>
                                            <td><?= $agent["contractor"]; ?></td>
                                            <td><?= $agent["department"]; ?></td>
                                            <td><?= $agent["email"]; ?></td>
                                            <td><?= $agent["phone_number"]; ?></td>
                                            <td><?= $agent["address"]; ?></td>
                                            <td><?= $agent["post_period"]; ?></td>
                                            <td>
                                                <a href="edit.php?id=<?= $agent['id'] ?>" class="btn btn-sm btn-primary">更新</a>
                                            </td>
                                            <td>
                                                <!-- 課題：/admin/delete.php?page=1,2 の形で削除ボタンを押さないと、confirmの確認なくデータが消えてしまう -->
                                                <!-- 課題解決：onclickに自作関数設定してphpでjsを発火させようとscriptタグ使っていたらheader関数の文法に反していた -->
                                                <a href="delete.php?id=<?= $agent['id'] ?>" class="btn btn-sm btn-danger" onClick="return confirm('エージェント情報を削除しますか？')">削除</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--ページネーション  -->
        <div class="pagination">
            <?php if ($page >= 2) : ?>
                <a href="index.php?page=<?php echo ($page - 1); ?>" class="page_feed">&laquo;</a>
            <?php else :; ?>
                <span class="first_last_page">&laquo;</span>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $max_page; $i++) : ?>
                <?php if ($i >= $page - $range && $i <= $page + $range) : ?>
                    <?php if ($i == $page) : ?>
                        <span class="now_page_number"><?php echo $i; ?></span>
                    <?php else : ?>
                        <a href="?page=<?php echo $i; ?>" class="page_number"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($page < $max_page) : ?>
                <a href="index.php?page=<?php echo ($page + 1); ?>" class="page_feed">&raquo;</a>
            <?php else : ?>
                <span class="first_last_page">&raquo;</span>
            <?php endif; ?>
        </div>
        <p class="from_to text-center mt-3"><?php echo $count['cnt']; ?>件中 <?php echo $from_record; ?> - <?php echo $to_record; ?> 件目を表示</p>
    </div>
    <!-- ログアウト機能作る -->
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- 私たちのJS -->
    <script src="../public/js/app.js"></script>
</body>

</html>