<?php
require('../dbconnect.php');
// 学生登録のフォームデータを受け取り、データベースにいれる
if (!empty($_POST["btn_submit"])) {
    try {
        // echo "<pre>";
        // var_dump($_POST);
        // echo "</pre>";
        // exit();
        $student_name = $_POST['student_name'];
        $student_university = $_POST['student_university'];
        $student_faculty = $_POST['student_faculty'];
        $student_department = $_POST['student_department'];
        $student_graduation = $_POST['student_graduation'];
        $student_phone_number = $_POST['student_tel'];
        $student_email = $_POST['student_email'];
        $student_address = $_POST['addr21'];
        $student_content = $_POST['student_content'];

        // studentsテーブルにinsert
        $sql = "INSERT INTO students (name, university, faculty, student_department, graduation, student_phone_number, student_email, student_address, content) VALUES
         (:name, :university, :faculty, :student_department, :graduation, :student_phone_number, :student_email, :student_address, :content)";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":name",  $student_name, PDO::PARAM_STR);
        $stmt->bindValue(":university",  $student_university, PDO::PARAM_STR);
        $stmt->bindValue(":faculty",  $student_faculty, PDO::PARAM_STR);
        $stmt->bindValue(":student_department",  $student_department, PDO::PARAM_STR);
        $stmt->bindValue(":graduation",  $student_graduation, PDO::PARAM_STR);
        $stmt->bindValue(":student_phone_number",  $student_phone_number, PDO::PARAM_STR);
        $stmt->bindValue(":student_email",  $student_email, PDO::PARAM_STR);
        $stmt->bindValue(":student_address",  $student_address, PDO::PARAM_STR);
        $stmt->bindValue(":content",  $student_content, PDO::PARAM_STR);
        $stmt->execute();

        // 中間テーブルに入力するデータ
        //どの企業をキープしたか 
        $keep_ids = $_POST['keep_agent_id'];
        // いま送られた学生情報の主キー
        $student_id_joinTable = $db->lastInsertId();

        // キープした企業の数だけ、中間テーブルにinsertする
        foreach ($keep_ids as $keep_id) {
            $sql = "INSERT INTO students_agents (student_id, agent_id) VALUES (:student_id, :agent_id)";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(":student_id",  $student_id_joinTable, PDO::PARAM_INT);
            $stmt->bindValue(":agent_id",  $keep_id, PDO::PARAM_INT);
            $stmt->execute();
        };

        // thanksページにリダイレクト
        header('Location: http://' . $_SERVER['HTTP_HOST'] . './thanks.php');
        exit();
    } catch (PDOException $e) {
        exit('データベースに接続できませんでした。' . $e->getMessage());
    }
}

// エージェントのログイン操作
session_start();
if (isset($_SESSION['employee_id']) && $_SESSION['time'] + 60 * 60 * 24 > time()) {
    $_SESSION['time'] = time();
} else {
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/admin/agent-login.php');
    exit();
}

// ------------------データベースの件数に対応させた動的ページネーション-----------------------------------------

//$count_sqlはデータの件数取得に使うための変数。
$count_sql = 'SELECT COUNT(*) as cnt FROM students';

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

// -----------------ページ切り替えごとに10件、該当エージェントに送られた学生情報を取得------------------------------------------------------------
$current_agent_id =  $_SESSION['agent_id'];
// echo $current_agent_id;

// エージェントの名前を取得
$stmt = $db->prepare('SELECT agent_name FROM agents INNER JOIN employees on agents.id = employees.agent_id WHERE employees.agent_id = :employees_agent_id');
$stmt->bindValue(":employees_agent_id", $current_agent_id, PDO::PARAM_INT);
$stmt->execute();
$current_agent_name = $stmt->fetch(PDO::FETCH_COLUMN);
// SELECT 申込者情報 FROM students 
// INNER JOIN 中間テーブル on students.id = 中間テーブル.student_id
// INNER JOIN agents on 中間テーブル.agent_id = agents.id

$page_change_record = $from_record - 1;
$stmt = $db->prepare('SELECT students.id, name, university, faculty, student_department, graduation, student_phone_number, student_email, student_address, content, students.created_at FROM students
INNER JOIN students_agents ON students.id = students_agents.student_id
INNER JOIN agents ON students_agents.agent_id = agents.id
WHERE agents.id = ?
LIMIT ?, 10 ');
$stmt->bindParam(1,  $current_agent_id, PDO::PARAM_INT);
$stmt->bindParam(2, $page_change_record, PDO::PARAM_INT);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 指定月の学生データを取得
if (isset($_POST["month_search"])) {
    $selected_search = $_POST["selected_month"];
    // var_dump($selected_search);
    // var_dump(date("Y/m", strtotime($selected_search)));
    $stmt = $db->prepare("SELECT students.id, name, university, faculty, student_department, graduation, student_phone_number, student_email, student_address, content, students.created_at FROM students
INNER JOIN students_agents ON students.id = students_agents.student_id
INNER JOIN agents ON students_agents.agent_id = agents.id
WHERE agents.id = :agent_id AND DATE_FORMAT(students.created_at, '%Y%m') = :selected_search
LIMIT :start_number, 10 ");
    $stmt->bindValue(":agent_id",  $current_agent_id, PDO::PARAM_INT);
    $stmt->bindValue(":start_number", $page_change_record, PDO::PARAM_INT);
    $stmt->bindValue(":selected_search", $selected_search, PDO::PARAM_INT);
    $stmt->execute();
    $students_selected_month = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    var_dump($students_selected_month);
    echo "</pre>";
    exit();
}
// 今月のお問い合わせ件数を取得
$stmt_month = $db->prepare("SELECT count(students.id) FROM students
INNER JOIN students_agents ON students.id = students_agents.student_id
INNER JOIN agents ON students_agents.agent_id = agents.id
WHERE agents.id = ? AND DATE_FORMAT(students.created_at, '%Y%m') = DATE_FORMAT(now(), '%Y%m')");
$stmt_month->bindParam(1,  $current_agent_id, PDO::PARAM_INT);
$stmt_month->execute();
$students_number_per_moth = $stmt_month->fetch(PDO::FETCH_COLUMN);

// 自社の情報だけを表示
$stmt = $db->prepare('SELECT id, agent_name, agent_url, representative, contractor, department, email, phone_number, address, post_period FROM agents
WHERE agents.id = ?');
$stmt->bindParam(1,  $current_agent_id, PDO::PARAM_INT);
$stmt->execute();
$my_agent = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>学生情報管理画面</title>
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

                <h1 class="ms-3 text-light">学生情報管理画面</h1>

                <span class="h5 text-light">ようこそ、<span class="h3 text-light"><?= $current_agent_name . " " ?></span>さん</span>
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
        <div class="d-flex justify-content-between">
            <h4 class="my-3">今月のお問い合わせ件数:
                <span class="px-2"><?= $students_number_per_moth ?></span>
                件
            </h4>
            <div class="d-flex my-3">
                <form action="" method="POST">
                    <select name="selected_month" id="graduation" class="text-secondary me-3" required>
                        <option value="" class="text-secondary default-word" hidden>選択してください</option>
                        <option value="202204" class="text-dark graduation">2022/04</option>
                        <option value="202205" class="text-dark graduation">2022/05</option>
                        <option value="202206" class="text-dark graduation">2022/06</option>
                        <option value="202207" class="text-dark graduation">2022/07</option>
                    </select>
                    <input class="btn btn-primary me-5" type="submit" name="month_search" value="指定月で検索">
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mb-lg-0">
                <div class="card">
                    <h5 class="card-header">学生情報</h5>
                    <!-- <?php echo $_SESSION['agent_id']; ?> -->
                    <div class="card-body pb-0">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">氏名</th>
                                        <th scope="col">大学名</th>
                                        <th scope="col">学部名</th>
                                        <th scope="col">学科名</th>
                                        <th scope="col">年度卒</th>
                                        <th scope="col">電話番号</th>
                                        <th scope="col">メールアドレス</th>
                                        <th scope="col">住所</th>
                                        <th scope="col">問い合わせ内容</th>
                                        <th>お問い合わせ日時</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $key => $student) : ?>
                                        <tr>
                                            <td><?= $student["name"]; ?></td>
                                            <td><?= $student["university"]; ?></td>
                                            <td><?= $student["faculty"]; ?></td>
                                            <td><?= $student["student_department"]; ?></td>
                                            <td><?= $student["graduation"]; ?></td>
                                            <td><?= $student["student_phone_number"]; ?></td>
                                            <td><?= $student["student_email"]; ?></td>
                                            <td><?= $student["student_address"]; ?></td>
                                            <td><?= $student["content"]; ?></td>
                                            <td><?= date("Y/m/d H:i", strtotime($student["created_at"])); ?></td>
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




        <!-- 自社情報の表示 -->
        <div class="row">
            <div class="col-12 mb-lg-0">
                <div class="card">
                    <h5 class="card-header">自社情報</h5>
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
                                    <?php foreach ($my_agent as $key => $my_agent_info) : ?>
                                        <tr>
                                            <td><?= $my_agent_info["agent_name"]; ?></td>
                                            <td><?= $my_agent_info["agent_url"]; ?></td>
                                            <td><?= $my_agent_info["representative"]; ?></td>
                                            <td><?= $my_agent_info["contractor"]; ?></td>
                                            <td><?= $my_agent_info["department"]; ?></td>
                                            <td><?= $my_agent_info["email"]; ?></td>
                                            <td><?= $my_agent_info["phone_number"]; ?></td>
                                            <td><?= $my_agent_info["address"]; ?></td>
                                            <td><?= $my_agent_info["post_period"]; ?></td>
                                            <td>
                                                <?php $sql = "SELECT name FROM tags inner join agents_tags on tags.id = agents_tags.tag_id inner join agents on agents_tags.agent_id = agents.id WHERE agents.id <=> :agent_id";
                                                $stmt_for_joinTable = $db->prepare($sql);
                                                //  エージェントの名前毎に、タグの名前を取得する
                                                $stmt_for_joinTable->bindValue(":agent_id", $current_agent_id, PDO::PARAM_STR);
                                                $stmt_for_joinTable->execute();
                                                $tags_with_agents = $stmt_for_joinTable->fetchAll(PDO::FETCH_COLUMN);
                                                foreach ($tags_with_agents as $key => $each_tag) {
                                                    echo $each_tag . ' ';
                                                } ?>
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
    <!-- ログアウト機能作る -->
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- 私たちのJS -->
    <script src="../public/js/app.js"></script>
</body>

</html>