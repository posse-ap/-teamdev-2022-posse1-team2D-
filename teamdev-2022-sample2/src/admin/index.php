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

// -------------------------------------------------------------------------------------------------------------
if (isset($_SESSION['user_id']) && $_SESSION['time'] + 60 * 60 * 24 > time()) {
    $_SESSION['time'] = time();

if (!empty($_POST)) {
    // 以下のechoは表示されない
    // echo __LINE__ . PHP_EOL;
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
        $deleted_at = $_POST['deleted_at'];
        // INSERT文を変数に格納。プレスホルダーは、値を入れるための空箱
        $sql = "INSERT INTO agents (agent_name, agent_url, representative, contractor, department, email, phone_number, address, post_period) VALUES 
        (:agent_name, :agent_url, :representative, :contractor, :department, :email, :phone_number, :address, :post_period)";
        $stmt = $db->prepare($sql); //挿入する値は空のまま、SQL実行の準備をする

        //方法１

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
        // $stmt->bindValue(":deleted_at",  $contractor, PDO::PARAM_INT);

        $stmt->execute();
        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/admin/index.php');
        exit();

    } catch (PDOException $e) {
        exit('データベースに接続できませんでした。' . $e->getMessage());
    }
}} else {
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/admin/login.php');
    exit();
}
// ------------------------------------------------------------------------------------------------------------
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

                <a class="navbar-brand fw-bold me-md-5 text-light" href="#">
                    <h1 class="mb-0">CRAFT</h1>
                    <div class="h6">by 就活.com</div>
                </a>

                <div class="float-end h5 text-light">
                    <div class="d-inline mx-5">○○様</div>
                    <a>ログアウト</a>
                </div>

            </div>
        </nav>
    </header>
    <!-- コンテンツ -->
    <div class="wrapper">
        <h1 class="ms-3">管理者ページ</h1>
        <form action="/admin/index.php" method="POST" class="ms-3">
            イベント名：<input class="d-block" type="text" name="title" required>
            <input class="d-block" type="submit" value="登録する">
        </form>
        <ul class="ms-3">
            <?php foreach ($events as $key => $event) : ?>
                <li>
                    <?= $event["id"]; ?>:<?= $event["title"]; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <!-- エージェント登録 -->
        <div class="content">
            <a class="js-modal-open h3" href="">エージェントの登録</a>
        </div>
        <div class="modal js-modal">
            <div class="modal__bg js-modal-close"></div>
            <div class="modal__content">
                <form action="/admin/index.php" method="POST" class="ms-3">
                    社名：<input class="d-block" type="text" name="agent_name" required>
                    会社URL：<input class="d-block" type="text" name="agent_url" required>
                    代表者名：<input class="d-block" type="text" name="representative" required>
                    契約担当者名：<input class="d-block" type="text" name="contractor" required>
                    部署：<input class="d-block" type="text" name="department" required>
                    メールアドレス：<input class="d-block" type="text" name="email" required>
                    電話番号：<input class="d-block" type="text" name="phone_number" required>
                    住所：<input class="d-block" type="text" name="address" required>
                    掲載期間：<input class="d-block" type="text" name="post_period" required>
                    表示状態：<input class="d-block" type="text" name="deleted_at" required>
                    <input class="d-block" type="submit" value="登録する">
                </form>
                <a class="js-modal-close" href="">閉じる</a>
            </div>
            <!--modal__inner-->
        </div>
        <!--modal-->

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
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>リクナビ</td>
                                        <td>CRAFT.com</td>
                                        <td>田中</td>
                                        <td>佐藤</td>
                                        <td>サンプル</td>
                                        <td>johndoe@gmail.com</td>
                                        <td>サンプル</td>
                                        <td>サンプル</td>
                                        <td>サンプル</td>
                                        <td><a href="#" class="btn btn-sm btn-primary">操作</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <nav aria-label="Page navigation example" class="mt-3">
            <ul class="pagination justify-content-center">
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
        <a href="/index.php" class="ms-5">イベント一覧</a>
    </div>
    <!-- ログアウト機能作る -->
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- 私たちのJS -->
    <script src="../public/js/app.js"></script>
</body>

</html>