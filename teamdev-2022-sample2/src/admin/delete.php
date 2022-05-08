<?php
require('../dbconnect.php');
require('index.php');
?>
<!-- confirmダイアログに応じて、データ削除イベントの実行分岐 -->
<script type="text/javascript">
   
function confirmFunction(){
    let result = confirm('エージェント情報を削除しますか');
    if (result == true) {
                console.log('削除されました');
                // 現在は物理削除。（phpで該当するレコードのdeleted_at = 0 を1に変更して論理削除)
                <?php
                if (isset($_GET["id"])) {
                    $id = (int) $_GET["id"];

                    try {
                        // 後で論理削除も試す
                        $sql = 'DELETE FROM agents WHERE id = :id';
                        $stmt = $db->prepare($sql);
                        //   $stmt->execute([":id" => $id]);
                        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                        $stmt->execute();
                        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/admin/index.php');
                        exit();
                    } catch (PDOException $e) {
                        exit('データベースに接続できませんでした。' . $e->getMessage());
                    }
                }
                ?>
                return true;
            } else {
                console.log('削除がキャンセルされました');
                return false;
            }
}
</script>
<?php
