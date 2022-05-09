<?php
require('../dbconnect.php');
// require('index.php');

if (isset($_GET["id"])) {
    $id = (int) $_GET["id"];

    try {
        // 後で論理削除も試す
        $sql = 'DELETE FROM agents WHERE id = :id';
        $stmt = $db->prepare($sql);
        //   $stmt->execute([":id" => $id]);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        // ここが機能していない
        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/admin/index.php?page=1');
        exit();
    } catch (PDOException $e) {
        exit('データベースに接続できませんでした。' . $e->getMessage());
    }
}
?>
<!-- confirmダイアログに応じて、データ削除イベントの実行分岐 -->

