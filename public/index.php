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

        <!-- scripts -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="/assets/datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="/assets/js/main.js"></script>

    </body>
</html>