<?php
require('../dbconnect.php');
if (isset($_POST)) {
    $id = (int) $_POST["agent_id"];

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
        $image = uniqid(mt_rand(), true); //ファイル名をユニーク化
        $image .= '.' . substr(strrchr($_FILES['logo']['name'], '.'), 1); //アップロードされたファイルの拡張子を取得
        $file = "images/$image";

        $sql = 'UPDATE agents SET
        agent_name = :agent_name, agent_url = :agent_url, representative = :representative, contractor = :contractor, department = :department, email = :email, phone_number = :phone_number, address =:address, post_period = :post_period, img = :img WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

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
        if (!empty($_FILES['logo']['name'])) { //ファイルが選択されていれば$imageにファイル名を代入
            move_uploaded_file($_FILES['logo']['tmp_name'], '../public/images/' . $image); //imagesディレクトリにファイル保存
            $stmt->execute();
        }

        // tagの更新を行う

        // そもそもエージェントにタグが存在しない場合は、updateできない & タグの数が1→2に変動する場合、updateの回数が対応しない
        // → まず該当エージェントのタグをすべて削除し、insertする形にすることで、更新を実現させる
        $tags = $_POST['tag'];
        $sql = 'DELETE FROM agents_tags WHERE agent_id = :agent_id';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":agent_id", $id, PDO::PARAM_INT);
        $stmt->execute();
        foreach ($tags as $tag) {
            $sql = "INSERT INTO agents_tags (agent_id, tag_id) VALUES (:agent_id, :tag_id)";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(":agent_id",  $id, PDO::PARAM_INT);
            $stmt->bindValue(":tag_id",  $tag, PDO::PARAM_INT);
            $stmt->execute();
        };
        // 管理者TOPページにリダイレクト
        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/admin/index.php?page=1');
        exit();
    } catch (PDOException $e) {
        exit('データベースに接続できませんでした。' . $e->getMessage());
    }
}
