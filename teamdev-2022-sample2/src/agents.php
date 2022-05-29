<?php
session_start();
require(dirname(__FILE__) . "/dbconnect.php");

try {
  $stmt = $db->prepare('SELECT agents.id, agent_name, agent_url, representative, address, email, img
 FROM agents
 INNER JOIN agents_tags ON agents.id = agents_tags.agent_id
 GROUP BY agents.id');
  $stmt->execute();
  $all_agents = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  exit('データベースに接続できませんでした。' . $e->getMessage());
}

$keep_count = $_SESSION['keep_count'];
$keep_count = intval($keep_count);

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <title>エージェント一覧</title>
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

        <a class="navbar-brand fw-bold me-md-5 text-light" href="index.php">
          <h1 class="mb-0">CRAFT</h1>
          <div class="h6">by 就活.com</div>
        </a>

        <div class="float-end">
          <!-- 法人ページ（ログインしている場合は管理画面、していない場合はログイン画面に遷移 -->
          <a href="/admin/index.php" class="h5 text-light d-none d-md-inline corporation-link">法人の方へ</a>
          <!-- キープマーク -->
          <a href="keep.php" class="keep-star ms-5">
            <i class="bi bi-star text-light" style="font-size: 1.6rem;"></i>
            <span class="d-inline bg-danger px-2 py-1 text-white circle"><?php echo $keep_count; ?></span>
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
  <div class="wrapper">
    <p class="first-size">エージェント企業一覧</p>
    <div class="row">
      <?php foreach ($all_agents as $key => $all_agent) : ?>
        <!-- エージェント毎のタグを全て取得する -->
        <? $stmt = $db->prepare('SELECT name FROM tags inner join agents_tags on tags.id = agents_tags.tag_id inner join agents on agents_tags.agent_id = agents.id WHERE agents.agent_name = :agent_name');
        $stmt->bindValue(":agent_name",  $all_agent['agent_name'], PDO::PARAM_STR);
        $stmt->execute();
        $all_agents_tags = $stmt->fetchAll(PDO::FETCH_COLUMN);
        ?>
        <!-- １つ１つのエージェントのカードタイル -->
        <div class="col-md-6 my-5 d-flex flex-row">
          <div class="rounded-start col-4 recommend-function d-flex align-items-center justify-content-center px-2">
            <img src="public/images/<?php echo $all_agent['img']; ?>" class="" alt="企業ロゴ">
          </div>
          <div class="col-5 col-md-6 result-content ps-3 my-0">
            <p class="second-size fw-bold"><?= $all_agent['agent_name']; ?></p>
            <p class="forth-size mb-0"><i class="bi bi-tags-fill"></i>タグ</p>
            <p class="forth-size">
              <?php foreach ($all_agents_tags as $key => $all_agents_tag) {
                echo $all_agents_tag, ' ';
                // echo $key;
              } ?>
            </p>
            <div class="mb-2">
              <a href="<?= $all_agent['agent_url']; ?>" class="forth-size" target="_blank" rel="noopener noreferrer">・公式サイト</a>
            </div>
          </div>
          <div class="rounded-end col-3 col-md-2 result-content d-flex flex-column justify-content-around align-items-end pe-3">
            <a href="agent-details/agent1.php?name=<?= $all_agent['agent_name']; ?>&url=<?= $all_agent['agent_url']; ?>&tag=<?php foreach ($all_agents_tags as $key => $all_agents_tag) {
                                                                                                                              echo $all_agents_tag . ' ';
                                                                                                                            } ?>&representative=<?= $all_agent['representative']; ?>&address=<?= $all_agent['address']; ?>&img=<?= $all_agent['img']; ?>" target="_blank" rel="noopener noreferrer" class="link-success"><i class="bi bi-cursor"></i>詳細へ</a>            
            <form action="" method="post">
              <!-- <button type="submit" class="keep-btn bi bi-star white-star keep">キープ</button> -->
            </form>
          </div>
        </div>
        <?php endforeach; ?>
    </div>
  </div>
  <!-- フッター -->
  <footer>
    <div id="footer">
      <div class="text-center">
        <a class="h1 mb-0 me-md-5 text-light" href="index.php">CRAFT</a>
      </div>
      <div class="text-center">
        <a class="h6 me-md-5 text-light" href="index.php">by 就活.com</a>
      </div>
      <div class="footer-nav">
        <ul class="ps-0">
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