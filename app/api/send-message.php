<?php

    include_once('../database.php');

    if (!empty($_POST)) {
        
        $link = connect_to_database();

        $sender = mysqli_real_escape_string($link, $_POST['sender']);
        $recipient = mysqli_real_escape_string($link, $_POST['recipient']);
        $text = mysqli_real_escape_string($link, $_POST['text']);
        $score = mysqli_real_escape_string($link, $_POST['score'] ?? 0);

        $query = "INSERT INTO `messages` (`sender`,`recipient`,`text`,`score`) VALUES ('".$sender."', '".$recipient."', '".$text."', '".$score."')";
        mysqli_query($link, $query);
    }

?>