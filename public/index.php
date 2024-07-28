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

        <form id="forecast-form" action="/" class="col-md-4">

            <div class="alert alert-danger d-none"></div>

            <div class="form-group">
                <label for="city">City name <span class="req">*</span></label>
                <input type="text" class="form-control" id="city" name="city">
            </div>

            <div class="form-group">
                <label for="date">Date</label>
                <input type="text" class="form-control datepicker" id="date" name="date">
            </div>

            <button type="submit" class="btn btn-primary mt-4">Submit</button>

        </form>

        <?php include __DIR__ . '/includes/table.php'; ?>

        <!-- scripts -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="/assets/datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="/assets/js/main.js"></script>

    </body>
</html>