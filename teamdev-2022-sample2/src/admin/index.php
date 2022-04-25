<?php
session_start();
require('../dbconnect.php');
if (isset($_SESSION['user_id']) && $_SESSION['time'] + 60 * 60 * 24 > time()) {
    $_SESSION['time'] = time();

    if (!empty($_POST)) {
        $stmt = $db->prepare('INSERT INTO events SET title=?');
        $stmt->execute(array(
            $_POST['title']
        ));

        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/admin/index.php');
        exit();
    }
} else {
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/admin/login.php');
    exit();
}
// admin/index.phpでinsert処理等したeventsテーブルから、id, titleを検索
$stmt = $db->query('SELECT id, title FROM events');
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <link href="public/css/style.css" rel="stylesheet">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body>
    <div>
        <h1 class="ms-3">管理者ページ</h1>
        <form action="/admin/index.php" method="POST" class="ms-3">
            イベント名：<input type="text" name="title" required>
            <input type="submit" value="登録する">
        </form>
        <ul class="ms-3">
            <?php foreach ($events as $key => $event) : ?>
                <li>
                    <?= $event["id"]; ?>:<?= $event["title"]; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <nav aria-label="Page navigation example" class="ms-3">
            <ul class="pagination">
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

        <!-- ログアウト機能作る -->
    </div>
</body>

</html>