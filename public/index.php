<?php 

require_once __DIR__ . '/../src/app.php';

$weather = new Weather();
$weather->run();

?><!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <title>≈Weather forecast</title>
            
        <!-- styles -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="/assets/datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
        <link href="/assets/css/main.css" rel="stylesheet">
    </head>
    <body class="p-4">

        <h1>≈Weather forecast</h1>

        <!-- form template -->
        <?php include __DIR__ . '/includes/_tpl-form.php'; ?>

        <!-- results container template -->
        <?php include __DIR__ . '/includes/_tpl-table.php'; ?>

        <!-- ajax spinner icon --> 
        <div id="spinner">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path d="M304 48a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zm0 416a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM48 304a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm464-48a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM142.9 437A48 48 0 1 0 75 369.1 48 48 0 1 0 142.9 437zm0-294.2A48 48 0 1 0 75 75a48 48 0 1 0 67.9 67.9zM369.1 437A48 48 0 1 0 437 369.1 48 48 0 1 0 369.1 437z"/>
            </svg>
        </div>

        <!-- scripts -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="/assets/datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="/assets/js/main.js"></script>

    </body>
</html>