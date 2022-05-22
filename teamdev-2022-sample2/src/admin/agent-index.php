<?php
session_start();
require('../dbconnect.php');
if (isset($_SESSION['user_id']) && $_SESSION['time'] + 60 * 60 * 24 > time()) {
    $_SESSION['time'] = time();

    // エージェント登録のフォームデータを受け取り、データベースにいれる
    if (!empty($_POST)) {
        try {

            // SELECT 申込者情報 FROM applicants 
            // INNER JOIN 中間テーブル on applicants.id = 中間テーブル.applicant_id
            // INNER JOIN agents on 中間テーブル.agent_id = agents.id
            // INNER JOIN employees on agents.id = employees.agent_id

            header('Location: http://' . $_SERVER['HTTP_HOST'] . '/admin/agent-index.php');
            exit();
        } catch (PDOException $e) {
            exit('データベースに接続できませんでした。' . $e->getMessage());
        }
    }
} else {
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/admin/agent-login.php');
    exit();
}
// htmlを表示、