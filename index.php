<?php

    if (array_key_exists('logout', $_GET)) {
        setcookie('teamName', '', time() - 24 * 60 * 60);
        header('location: index.php');
    }

    if (array_key_exists('teamName', $_COOKIE)) {
        header('location: play.php');
    }

    $error = "";

    if (array_key_exists('teamName', $_POST)) {
        $team_name = $_POST['teamName'];

        if (strlen($team_name) < 5)
            $error = "Your team name must be at least 5 characters long.";
        elseif (strlen($team_name) > 50)
            $error = "Your team name must be fewer than 50 characters long.";
        elseif (strpos($team_name, '"'))
            $error = "Your team name cannot contain quotation marks.";
        else {
            setcookie('teamName', $team_name, time() + 12 * 60 * 60);
            header('location: play.php');
        }
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <title>Trivia by Torgo Entertainment</title>
    </head>
    <body>
        <div class="container">
            <h2>Welcome to Team Trivia</h2>
            <h4>Hosted by Torgo Entertainment</h4>

            <form method="POST">
                <?php
                    if ($error != "")
                        echo '<div class="alert alert-warning">'.$error.'</div>'
                ?>

                <div class="form-group">
                    <label for="teamName">Team Name</label>
                    <input type="text" class="form-control" id="teamName" name="teamName" placeholder="Enter a team name" autocomplete="off" />
                </div>

                <button type="submit" class="btn btn-primary btn-lg">Submit</button>
            </form>
        </div>
    
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>