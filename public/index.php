<?php 

require_once __DIR__ . '/../src/app.php';

$weather = new Weather();
dd( $weather->validate( 1.21, 84 ) );

echo 'it works';