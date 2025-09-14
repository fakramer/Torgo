<?php

    include_once('../database.php');

    function get_messages($link, $teamName) {
        $name = mysqli_real_escape_string($link, $teamName);

        $query = "SELECT `id`,`timestamp`,`sender`,`recipient`,`text`,`score` FROM `messages` WHERE (`recipient`='".$name."' OR `sender`='".$name."') AND timestamp >= CURRENT_TIMESTAMP() - INTERVAL 12 HOUR ORDER BY `timestamp`";
        $result = mysqli_query($link, $query);

        $messages = array();
        while ($row = mysqli_fetch_array($result)) {
            $message = array(
                'id' => $row['id'],
                'timestamp' => $row['timestamp'],
                'sender' => $row['sender'],
                'recipient' => $row['recipient'],
                'text' => $row['text'],
                'score' => $row['score']
            );
            array_push($messages, $message);
        }
        
        return $messages;
    }

    function get_score($link, $teamName) {
        $name = mysqli_real_escape_string($link, $teamName);

        $query = "SELECT SUM(`score`) total_score FROM `messages` WHERE (`recipient`='".$name."') AND timestamp >= CURRENT_TIMESTAMP() - INTERVAL 12 HOUR ORDER BY `timestamp`";
        $result = mysqli_query($link, $query);

        if ($row = mysqli_fetch_array($result)) {
            return $row['total_score'];
        }
        
        return 0;
    }
    
    // Set headers to prevent caching
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');

    $teamName = $_COOKIE['teamName'];

    if (!$teamName) {
        header('HTTP/1.0 401 Unauthorized');
        die('Team name is required.');
    }

    $link = connect_to_database();

    $response = array(
        'messages' => get_messages($link, $teamName),
        'score' => get_score($link, $teamName),
        'teamName' => $teamName,
    );
    
    print(json_encode($response));
    
?>