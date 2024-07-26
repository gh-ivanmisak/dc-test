<?php

// basic constants
if( false == defined('_ROOT_DIR_') ){ define('_ROOT_DIR_', __DIR__ . '/..'); }
if( false == defined('_WWW_DIR_') ){ define('_WWW_DIR_', _ROOT_DIR_ . '/public'); }

// composer
require_once _ROOT_DIR_ . '/vendor/autoload.php';

// custom code, libraries, etc.
require_once _ROOT_DIR_ . '/src/autoload.php';