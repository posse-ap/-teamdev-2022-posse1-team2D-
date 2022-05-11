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

        $sql = 'UPDATE agents SET
        agent_name = :agent_name, agent_url = :agent_url, representative = :representative, contractor = :contractor, department = :department, email = :email, phone_number = :phone_number, address =:address, post_period = :post_period WHERE id = :id';
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
        $stmt->execute();
        // 管理者TOPページにリダイレクト
        header('Location: http://' . $_SERVER['HTTP_HOST'] . '/admin/index.php?page=1');
        exit();
    } catch (PDOException $e) {
        exit('データベースに接続できませんでした。' . $e->getMessage());
    }
}
