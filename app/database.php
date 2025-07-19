<?php
    function connect_to_database() {
        $env = parse_ini_file(__DIR__ . '/.env');
        return mysqli_connect($env['DB_HOST'], $env['DB_USER'], $env['DB_PASS'], $env['DB_NAME']);
    }
?>