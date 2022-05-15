<?php
require('../dbconnect.php');

if ($_POST) {
    try {
        $search_word = $_POST['word'];
        if ($search_word == "") {
            echo "input search word";
        } else {
            $sql = "SELECT id, agent_name, agent_url, representative, contractor, department, email, phone_number, address, post_period FROM agents WHERE agent_name like '".$search_word."%'";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
            if ($result) {
                foreach ($result as $row) {
                    echo $row['agent_name'] . " ";
                    echo $row['agent_url'] . " ";
                    echo $row['representative'] . " ";
                    echo $row['contractor'] . " ";
                    echo $row['department'] . " ";
                    echo $row['email'] . " ";
                    echo $row['phone_number'] . " ";
                    echo $row['address'] . " ";
                    echo $row['post_period'] . " ";
                }
            } else {
                echo "not found";
            }
        }
    } catch (PDOException $e) {
        echo  "<p>Failed : " . $e->getMessage() . "</p>";
        exit();
    }
}
