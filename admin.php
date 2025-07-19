<?php

    if (!array_key_exists('passcode', $_GET)) {
        die('Nope.');
    }

    $passcode = $_GET['passcode'];

    $env = parse_ini_file(__DIR__ . '/.env');
    if ($passcode != $env['ADMIN_PASS'])
        die('Nope.');

    setcookie('teamName', 'DJ Torgo', time() + 12 * 60 * 60);
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
            .torgo-message {
                text-align: right;
                color: blue;
            }

            .btn-expand, .btn-collapse {
                margin-right: 12px;
            }
        </style>

        <title>Trivia by Torgo Entertainment</title>
    </head>
    <body>
        <div class="container">
            <h2>Host Page</h2>
            
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="score">Score</label>
                    <input type="number" class="form-control" id="score" />
                </div>
                <div class="btn-group" role="group">
                    <button class="btn btn-secondary" onclick="updateScore(-5);">-5</button>
                    <button class="btn btn-secondary" onclick="updateScore(-1);">-1</button>
                    <button class="btn btn-secondary" onclick="updateScore(1);">+1</button>
                    <button class="btn btn-secondary" onclick="updateScore(5);">+5</button>
                </div>
            </div>
            <p>
                <ul class="list-group" id="message-log"></ul>
            </p>
        </div>

        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <!-- Optional JavaScript -->
        <script type="text/javascript">

            const correctText = 'Yes';
            const partialText = 'Part';
            const incorrectText = 'No';
            const okText = 'Ok';
            const promptText = 'More';

            function teamInterfaceHtml(teamName) {
                return '<li class="list-group-item team-interface" data-team="' + teamName + '">' +
                    handleHtml(teamName) +
                    replyBarHtml() +
                    messageListHtml() +
                    '</li>';
            }

            function handleHtml(teamName) {
                return  '<h4>' + 
                        '<button class="btn btn-primary btn-expand">&plus;</i></button>' +
                        '<button class="btn btn-primary btn-collapse" hidden>&minus;</i></button>' +
                        `${teamName} (<span class='teamScore'></span>)` +
                        '<span class="badge badge-pill badge-secondary float-right new-messages">0</span>' +
                    '</h4>';
            }

            function replyBarHtml() {
                return '<div class="form-group reply-bar" hidden>' + 
                            '<div class="input-group">' + 
                                '<div class="input-group-prepend">' +
                                    `<button type="button" class="btn btn-success btn-correct">${correctText}</button>` +
                                    `<button type="button" class="btn btn-warning btn-partial">${partialText}</button>` +
                                    `<button type="button" class="btn btn-danger btn-incorrect">${incorrectText}</button>` +
                                    `<button type="button" class="btn btn-info btn-ok">${okText}</button>` +
                                    `<button type="button" class="btn btn-secondary btn-prompt">${promptText}</button>` +
                                '</div>' +
                                '<input type="text" class="form-control reply-text" autocomplete="off" />' +
                                '<div class="input-group-append">' +
                                    '<button type="button" class="btn btn-primary btn-send">Send</button>' +
                                '</div>' +
                            '</div>' +
                        '</div>';
            }

            function messageListHtml() {
                return '<ul class="list-group message-list" hidden></ul>';
            }
        
            function send(sender, score) {
                const teamInterface = sender.parents('.team-interface');
                const recipient = teamInterface.attr('data-team');
                const text = teamInterface.find('input.reply-text').val();

                message = {
                    sender: "DJ Torgo",
                    recipient,
                    text,
                    score
                };

                console.log(message);

                $.post('api/send-message.php', message);

                teamInterface.find('input').val('');
            }

            function getCurrentScore() {
                const currentScore = Number($('#score').val());

                if (currentScore)
                    return currentScore;
                
                return 0;
            }

            function updateScore(change) {
                const updatedScore = getCurrentScore() + change;

                $('#score').val(updatedScore);
                
                updateResponseButtonText();
            }

            function updateResponseButtonText() {
                const updatedScore = getCurrentScore();

                if (updatedScore > 0) {
                    $('.btn-correct').html(`${correctText} (+${updatedScore})`);
                    $('.btn-partial').html(`${partialText} (+${updatedScore})`);
                    $('.btn-incorrect').html(`${incorrectText}`);
                }
                else if (updatedScore < 0) {
                    $('.btn-correct').html(`${correctText}`);
                    $('.btn-partial').html(`${partialText}`);
                    $('.btn-incorrect').html(`${incorrectText} (${updatedScore})`);
                }
                else {
                    $('.btn-correct').html(`${correctText}`);
                    $('.btn-partial').html(`${partialText}`);
                    $('.btn-incorrect').html(`${incorrectText}`);
                }
            }

            function formatMessage(text, score) {
                if (score > 0)
                    return `${text} (+${score})`;
                else if (score < 0)
                    return `${text} (${score})`;
                else
                    return text;
            }

            $(document).ready(function() {

                $('body').on('click', '.btn-expand', function () {
                    const teamInterface = $(this).parents('li.team-interface');
                    teamInterface.find('.btn-expand').prop('hidden', true);
                    teamInterface.find('.btn-collapse').prop('hidden', false);
                    teamInterface.find('.reply-bar').prop('hidden', false);
                    teamInterface.find('.message-list').prop('hidden', false);
                    teamInterface.find('.btn-see-more').prop('hidden', false);

                    teamInterface.find('.new-messages').addClass('badge-secondary');
                    teamInterface.find('.new-messages').removeClass('badge-danger');
                });

                $('body').on('click', '.btn-collapse', function () {
                    const teamInterface = $(this).parents('li.team-interface');
                    teamInterface.find('.btn-expand').prop('hidden', false);
                    teamInterface.find('.btn-collapse').prop('hidden', true);
                    teamInterface.find('.reply-bar').prop('hidden', true);
                    teamInterface.find('.message-list').prop('hidden', true);
                    teamInterface.find('.btn-see-more').prop('hidden', true);
                });

                $('body').on('click', '.btn-send', function () {
                    send($(this));
                });

                $('body').on('click', '.btn-correct', function () {
                    let teamInterface = $(this).parents('div.reply-bar');
                    let replyText = teamInterface.find('.reply-text');
                    replyText.val('Correct');

                    const score = getCurrentScore();
                    const applyScore = score > 0 ? score : null;

                    send($(this), applyScore);
                });

                $('body').on('click', '.btn-partial', function () {
                    let teamInterface = $(this).parents('div.reply-bar');
                    let replyText = teamInterface.find('.reply-text');
                    replyText.val('Partial');

                    const score = getCurrentScore();
                    const applyScore = score > 0 ? score : null;

                    send($(this), applyScore);
                });

                $('body').on('click', '.btn-incorrect', function () {
                    let teamInterface = $(this).parents('div.reply-bar');
                    let replyText = teamInterface.find('.reply-text');
                    replyText.val('Incorrect');

                    const score = getCurrentScore();
                    const applyScore = score < 0 ? score : null;

                    send($(this), applyScore);
                });

                $('body').on('click', '.btn-ok', function () {
                    let teamInterface = $(this).parents('div.reply-bar');
                    let replyText = teamInterface.find('input.reply-text');
                    replyText.val('Acknowledged');

                    send($(this));
                });

                $('body').on('click', '.btn-prompt', function () {
                    let teamInterface = $(this).parents('div.reply-bar');
                    let replyText = teamInterface.find('input.reply-text');
                    replyText.val('Prompt');

                    send($(this));
                });

                $('#score').change(function () {
                    updateResponseButtonText();
                });
                
                setInterval(() => {
                    $.get('api/view-messages.php', { name: 'DJ Torgo' }, data => {

                        const response = JSON.parse(data);
                        const messages = response.messages;

                        $.each(messages, (index, value) => {
                            const { id, sender, text, score } = value;
                            const team = (sender === 'DJ Torgo') ? value['recipient'] : sender;
                            const formattedText = formatMessage(text, score);

                            let teamInterface = $('#message-log').find('li.team-interface[data-team="' + team + '"]')[0];

                            if (typeof teamInterface === 'undefined') {
                                $('#message-log').append(teamInterfaceHtml(team));
                                teamInterface = $('#message-log').find('li.team-interface[data-team="' + team + '"]')[0];
                            }
                            
                            let message = $(teamInterface).find('li.message[data-message-id="' + id + '"]')[0];

                            if (typeof message === 'undefined') {
                                if (sender === 'DJ Torgo') {
                                    if (score) {
                                        const teamScore = $(teamInterface).find('.teamScore');
                                        teamScore.html(Number(teamScore.html()) + Number(score));
                                        console.log(teamScore.html());
                                    }

                                    $(teamInterface).find('.message-list').prepend('<li class="list-group-item message torgo-message" data-message-id="' + id + '">' + formattedText + '</li>');
                                    $(teamInterface).find('.new-messages').html('0');
                                    $(teamInterface).find('.new-messages').addClass('badge-secondary');
                                    $(teamInterface).find('.new-messages').removeClass('badge-danger');
                                }
                                else {
                                    $(teamInterface).find('.message-list').prepend('<li class="list-group-item message" data-message-id="' + id + '">' + text + '</li>');
                                    const newMessages = parseInt($(teamInterface).find('.new-messages').html());
                                    $(teamInterface).find('.new-messages').html(newMessages + 1);
                                    $(teamInterface).find('.new-messages').addClass('badge-danger');
                                    $(teamInterface).find('.new-messages').removeClass('badge-secondary');
                                }
                            }
                        });
                    });
                }, 3000);
            });
        </script>
    </body>
</html>
