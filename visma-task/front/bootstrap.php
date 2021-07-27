<?php
session_start();
define('URL', 'http://localhost/visma-task/front/'); // <--- konstanta
define('DIR', __DIR__.'/');

var_dump(DIR);

define('INSTALL_DIR', '/box2/');


require DIR . 'app/FrontController.php';
require DIR. 'app/Helper.php';




// _d($_SESSION, 'SESIJA--->');

// http://localhost/zuikis/box/



