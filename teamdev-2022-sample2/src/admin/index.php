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
            // var_dump($_FILES);
            $image = uniqid(mt_rand(), true); //ファイル名をユニーク化
            $image .= '.' . substr(strrchr($_FILES['logo']['name'], '.'), 1); //アップロードされたファイルの拡張子を取得
            $file = "images/$image";
            // フォームからファイルの中身が一時的にどこにあるか確認
            // imgの名前、どこにあるのかのパスはDB、画像のデータはimgディレクトリで管理

            // INSERT文を変数に格納。プレスホルダーは、値を入れるための空箱
            $sql = "INSERT INTO agents (agent_name, agent_url, representative, contractor, department, email, phone_number, address, post_period, img) VALUES 
        (:agent_name, :agent_url, :representative, :contractor, :department, :email, :phone_number, :address, :post_period, :img)";
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

            $stmt->bindValue(":img", $image, PDO::PARAM_STR);
            // 
            if (!empty($_FILES['logo']['name'])) {//ファイルが選択されていれば$imageにファイル名を代入
                move_uploaded_file($_FILES['logo']['tmp_name'], '../public/images/' . $image);//imagesディレクトリにファイル保存
                $stmt->execute();
            }

            // tagの登録を行う

            // 中間テーブルに入力するデータ
            $tags = $_POST['tag'];
            // 最後に入力されたエージェントの主キーを取得
            $agent_id_joinTable = $db->lastInsertId();
            // 選択したタグの数だけ、中間テーブルにinsertする
            foreach ($tags as $tag) {
                $sql = "INSERT INTO agents_tags (agent_id, tag_id) VALUES (:agent_id, :tag_id)";
                $stmt = $db->prepare($sql);
                $stmt->bindValue(":agent_id",  $agent_id_joinTable, PDO::PARAM_INT);
                $stmt->bindValue(":tag_id",  $tag, PDO::PARAM_INT);
                $stmt->execute();
            };

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
$stmt = $db->prepare('SELECT id, agent_name, agent_url, representative, contractor, department, email, phone_number, address, post_period,  img FROM agents WHERE deleted_at = 0 LIMIT ?, 10');
$stmt->bindParam(1, $page_change_record, PDO::PARAM_INT);
$stmt->execute();
$agents = $stmt->fetchAll(PDO::FETCH_ASSOC);
// tagを取得する
$stmt = $db->query('SELECT id, name FROM tags');
$tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
// -------------------------------------------------------------------------------------------------------------
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>エージェント情報管理画面</title>
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
                    <form method="get" action="">
                        <input type="submit" name="btn_logout" value="ログアウト">
                    </form>
                </div>

            </div>
        </nav>
    </header>
    <!-- コンテンツ -->
    <div class="admin-wrapper">
        <!-- エージェント登録 -->
        <div class="row">
            <div class="content col-6">
                <a class="js-modal-open btn btn-lg btn-success" href="">エージェントの登録</a>
            </div>
            <!--エージェント登録用のモーダル-->
            <div class="modal js-modal">
                <div class="modal__bg js-modal-close"></div>
                <!--モーダルの構成-->
                <div class="modal__content">
                    <h5 class="modal-top" id="exampleModalLabel">エージェント情報の登録を行います</h5>
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                    <form action="/admin/index.php" method="POST" class="ms-3" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-6">
                                社名：<input class="d-block" type="text" name="agent_name" required>
                                会社URL：<input class="d-block" type="url" name="agent_url" placeholder="http://example.jp" required>
                                代表者名：<input class="d-block" type="text" name="representative" required>
                                契約担当者名：<input class="d-block" type="text" name="contractor" required>
                                部署：<input class="d-block" type="text" name="department" required>
                                メールアドレス：<input class="d-block" type="email" name="email" placeholder="info@example.com" required>
                                電話番号：<input class="d-block" type="tel" name="phone_number" placeholder="電話番号" required>
                                住所：<input class="d-block" type="text" name="address" required>
                            </div>
                            <div class="col-6">
                                掲載期間：<input class="d-block" type="date" name="post_period" required>
                                企業ロゴ：<input class="d-block" type="file" name="logo" accept="image/*" required>
                                <div class="h6 mt-2">タグの選択</div>
                                <?php foreach ($tags as $key => $tag) : ?>
                                    <input type="checkbox" name="tag[]" value="<?= $tag["id"]; ?>" class="form-check-input me-1 h5" id="flexCheckDefault"><?= $tag["name"]; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="modal-bottom">
                            <a class="js-modal-close btn btn-secondary mx-2" href="">閉じる</a>
                            <input class="d-block btn btn-success" type="submit" value="登録する">
                        </div>
                    </form>
                </div>
            </div>

            <div class="content col-6">
                <form action="/admin/buzzer_search.php" method="POST">
                    <label>Name：</label>
                    <input type="text" name="word" /><input type="submit" value="検索" />
                </form>
            </div>
        </div>


        <div class="row">
            <div class="col-12 mb-lg-0">
                <div class="card">
                    <h5 class="card-header">契約エージェント一覧</h5>
                    <div class="card-body pb-0">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">社名</th>
                                        <th scope="col">会社URL</th>
                                        <th scope="col">代表者名</th>
                                        <th scope="col">契約担当者名</th>
                                        <th scope="col">部署</th>
                                        <th scope="col">メールアドレス</th>
                                        <th scope="col">電話番号</th>
                                        <th scope="col">住所</th>
                                        <th scope="col">掲載期間</th>
                                        <th scope="col">タグ</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($agents as $key => $agent) : ?>
                                        <tr>
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
                                                <?php $sql = "SELECT name FROM tags inner join agents_tags on tags.id = agents_tags.tag_id inner join agents on agents_tags.agent_id = agents.id WHERE agents.agent_name <=> :agent_name";
                                                $stmt_for_joinTable = $db->prepare($sql);
                                                //  エージェントの名前毎に、タグの名前を取得する
                                                $stmt_for_joinTable->bindValue(":agent_name", $agent["agent_name"], PDO::PARAM_STR);
                                                $stmt_for_joinTable->execute();
                                                $tags_with_agents = $stmt_for_joinTable->fetchAll(PDO::FETCH_COLUMN);
                                                foreach ($tags_with_agents as $key => $each_tag) {
                                                    echo $each_tag . ' ';
                                                } ?>
                                            </td>
                                            <td>
                                                <!-- エージェント更新ボタン -->
                                                <button type="button" class="btn btn-sm btn-primary modal-trigger" data-bs-toggle="modal" data-bs-target="#exampleModal" data-whatever="<?= $agent['id'] ?>">
                                                    更新
                                                </button>
                                                <!-- エージェント更新用のモーダル -->
                                                <div class="modal fade modal-index" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">エージェント情報の更新を行います</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="edit.php?id=" method="POST" enctype="multipart/form-data">
                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                            <input id="agent-number" class="d-block" type="hidden" name="agent_id" value="" required>
                                                                            社名：<input class="d-block" type="text" name="agent_name" required>
                                                                            会社URL：<input class="d-block" type="url" name="agent_url" placeholder="http://example.jp" required>
                                                                            代表者名：<input class="d-block" type="text" name="representative" required>
                                                                            契約担当者名：<input class="d-block" type="text" name="contractor" required>
                                                                            部署：<input class="d-block" type="text" name="department" required>
                                                                            メールアドレス：<input class="d-block" type="email" name="email" placeholder="info@example.com" required>
                                                                            電話番号：<input class="d-block" type="tel" name="phone_number" placeholder="電話番号" required>
                                                                            住所：<input class="d-block" type="text" name="address" required>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            掲載期間：<input class="d-block" type="date" name="post_period" required>
                                                                            企業ロゴ：<input class="d-block" type="file" name="logo" accept="image/*" required>
                                                                            <div class="h6 mt-2">タグの選択</div>
                                                                            <?php foreach ($tags as $key => $tag) : ?>
                                                                                <input type="checkbox" name="tag[]" value="<?= $tag["id"]; ?>" class="form-check-input me-1 h5" id="flexCheckDefault"><?= $tag["name"]; ?>
                                                                            <?php endforeach; ?>
                                                                        </div>
                                                                    </div>

                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary modal-close" data-bs-dismiss="modal">戻る</button>
                                                                        <input class="btn btn-primary" type="submit" value="更新する">
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <!-- エージェント削除ボタン -->
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