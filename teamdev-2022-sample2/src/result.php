<?php
require(dirname(__FILE__) . "/dbconnect.php");
if (isset($_POST['tag'])) {
  try {
    // 選択したタグのidを取得
    $tags = $_POST['tag'];
    $arr_tag_id = array();
    foreach ($tags as $tag) {
      $sql = "SELECT id, name FROM tags where id = :tag_id";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(":tag_id",  $tag, PDO::PARAM_INT);
      $stmt->execute();
      array_push($arr_tag_id, $tag . ",");
    };
    // 送信されたタグのidをカンマ区切りの文字列に変換する
    $str_tags = implode($arr_tag_id);
    // エージェントの情報を送信されたタグの数にヒットした順に取得する
    $stmt = $db->prepare('SELECT agent_name, agent_url, COUNT(*) AS count, representative, address, email
    FROM agents
    INNER JOIN agents_tags ON agents.id = agents_tags.agent_id 
    WHERE FIND_IN_SET(agents_tags.tag_id, :tags) 
    GROUP BY agents.id
    ORDER BY count DESC');
    $stmt->bindValue(":tags",  $str_tags, PDO::PARAM_STR);
    $stmt->execute();
    $result_agents = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    exit('データベースに接続できませんでした。' . $e->getMessage());
  }
}
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
              <a class="h6 nav-link text-dark" href="agents.php">エージェント一覧</a>
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
              <a class="h6 nav-link text-dark" href="contact.php">boozerへのお問い合わせ<i class="bi bi-pencil-square"></i></a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
  <!-- コンテンツ -->
  <div class="wrapper">
    <p class="h2 fw-bold text-center">検索結果一覧</p>
    <div class="d-inline h4 me-3">選択したタグ: </div>
    <?php foreach ($tags as $tag) : ?>
      <? $sql = "SELECT id, name FROM tags where id = :tag_id";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(":tag_id",  $tag, PDO::PARAM_INT);
      $stmt->execute();
      $checked_tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
      ?>
      <!-- なんのタグを選択したか表示 -->
      <?php foreach ($checked_tags as $checked_tag) : ?>
        <div class="d-inline h4 me-2"><?= $checked_tag['name']; ?></div>
      <?php endforeach; ?>

    <?php endforeach; ?>
    <div class="row">
      <!-- ヒットしたエージェントの数だけ、以下のphp動作と、html要素をforeachさせる -->
      <?php foreach ($result_agents as $key => $result_agent) : ?>
        <!-- エージェント毎のタグを全て取得する -->
        <? $stmt = $db->prepare('SELECT name FROM tags inner join agents_tags on tags.id = agents_tags.tag_id inner join agents on agents_tags.agent_id = agents.id WHERE agents.agent_name = :agent_name');
        $stmt->bindValue(":agent_name",  $result_agent['agent_name'], PDO::PARAM_STR);
        $stmt->execute();
        $result_agents_tags = $stmt->fetchAll(PDO::FETCH_COLUMN);
        ?>
        <!-- １つ１つのエージェントのカードタイル -->
        <div class="col-md-6 my-3 my-md-4 d-flex flex-row">
          <div class="rounded-start col-4 recommend-function d-flex align-items-center justify-content-center px-2">
            <div class="">
              <img src="public/img/feature5.jpg" class="" alt="">
            </div>
          </div>
          <div class="col-5 col-md-6 result-content ps-3 my-0">
            <p class="second-size fw-bold"><?= $result_agent['agent_name']; ?></p>
            <p class="forth-size mb-0"><i class="bi bi-tags-fill"></i>タグ</p>
            <p class="forth-size">
              <?php foreach ($result_agents_tags as $key => $result_agents_tag) {
                echo $result_agents_tag . ' ';
              } ?>
            </p>
            <div class="mb-2">
              <a href="<?= $result_agent['agent_url']; ?>" class="forth-size" target="_blank" rel="noopener noreferrer">・公式サイト</a>
            </div>
          </div>
          <div class="rounded-end col-3 col-md-2 result-content d-flex flex-column justify-content-around align-items-end pe-3">
            <a href="agent-details/agent1.php?name=<?= $result_agent['agent_name']; ?>&url=<?= $result_agent['agent_url']; ?>&tag=<?php foreach ($result_agents_tags as $key => $result_agents_tag) {echo $result_agents_tag . ' ';} ?>&representative=<?= $result_agent['representative']; ?>&address=<?= $result_agent['address']; ?>" target="_blank" rel="noopener noreferrer" class="link-success"><i class="bi bi-cursor"></i>詳細へ</a>
            <!-- <button class="keep-btn"><i class="bi bi-star-fill black-star"></i><i class="bi bi-star white-star"></i>キープする</button> -->
            <!-- キープした時にセッションでエージェントの情報を保持 -->
            <div class="">
              <form action="" method="POST" class="item-form">
                <?php foreach ($tags as $tag) : ?>
                  <input type="hidden" name="tag[]" value="<?= $tag ?>">
                <?php endforeach; ?>
                <input type="hidden" name="email" value="<?= $result_agent['email']; ?>">
                <input type="hidden" name="name" value="<?= $result_agent['agent_name']; ?>">
                <button type="submit" class="keep-btn bi bi-star white-star">キープ</button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
      <!-- ⚠cardは角丸いのに背景の色は角ばってる⚠ -->
    </div>
  </div>
  </div>
  <!--キープした時にエージェントの名前とメールがとれたかデバッグ、この変数をセッションで管理する  -->
  <?php
  $keep_email = isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'utf-8') : '';
  $keep_name = isset($_POST['name']) ? htmlspecialchars($_POST['name'], ENT_QUOTES, 'utf-8') : '';
  echo $keep_email;
  echo '<pre></pre>';
  echo $keep_name;
  ?>
  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <!-- 私たちのJS -->
  <script src="public/js/app.js"></script>
</body>

</html>