<?php

    if (!array_key_exists('teamName', $_COOKIE)) {
        header('location: index.php');
    }

    $team_name = $_COOKIE['teamName'];

?>

<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <style type="text/css">
            .my-message {
                text-align: right;
                color: blue;
            }

            .other-message {
                text-align: left;
                color: green;
            }
        </style>

        <title>Trivia by Torgo Entertainment</title>
    </head>
    <body>
        <div class="container">
            <h4 class="float-right">Score: <span id="score"></span></h4>
            <h2><?php echo $team_name; ?></h2>
            
            <a href="index.php?logout" id="a-change-name">Change team name</a>

            <div class="form-group">
                <label for="submission">Submission</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="submission" name="submission" autocomplete="off" />
                    <div class="input-group-append">
                        <button type="button" class="btn btn-primary btn-lg" id="btn-send">Send</button>
                    </div>
                </div>
            </div>

            <ul class="list-group" id="message-log"></ul>
        </div>
    

        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <!-- Optional JavaScript -->
        <script type="text/javascript">

            function formatMessage(text, score) {
                if (score > 0)
                    return `${text} (+${score})`;
                else if (score < 0)
                    return `${text} (${score})`;
                else
                    return text;
            }
            
            $(document).ready(function() {

                $('#btn-send').click(function () {
                    let text = $('#submission').val();

                    if (text === '') {
                        alert('Enter text for your submission.');
                        return;
                    }

                    message = {
                        sender: "<?php echo $team_name; ?>",
                        recipient: "DJ Torgo",
                        text
                    };

                    $.post('api/send-message.php', message);

                    $('#submission').prop('disabled', true);
                    $('#btn-send').prop('disabled', true);
                    $('#btn-send').html('Sending...');
                });
               
                setInterval(() => {
                    $.get('api/view-messages.php', data => {

                        const response = JSON.parse(data);
                        const { messages, score } = response;

                        $('#score').html(score);

                        if (messages.length > 0)
                            $('#a-change-name').prop('hidden', true);

                        messageLogHtml = '';
                        $.each(messages, (index, value) => {
                            const { text, score } = value;

                            senderClass = value['sender'] === "<?php echo $team_name; ?>" ? 'my-message' : 'other-message';
                            const displayText = formatMessage(text, score);
                            messageLogHtml = '<li class="list-group-item"><p class="' + senderClass + '">' + displayText + '</p></li>' + messageLogHtml;
                        });

                        $('#message-log').html(messageLogHtml);

                        if ($('#submission').prop('disabled')) {
                            $('#btn-send').html('Send');
                            $('#btn-send').prop('disabled', false);
                            $('#submission').prop('disabled', false);
                            $('#submission').val('');
                        }
                    });
                }, 3000);
            });
        </script>
    </body>
</html>