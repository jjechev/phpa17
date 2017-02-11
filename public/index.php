<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('../core/autoload.php');
require('../config.php');
require('../core/Router.php');
require('../core/functions.php');

Router::init();
