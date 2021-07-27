<?php
require __DIR__.'/bootstrap.php';

$uri = explode('/',str_replace(INSTALL_DIR, '', $_SERVER['REQUEST_URI']));

//var_dump(str_replace(INSTALL_DIR, '', $_SERVER['REQUEST_URI']));
//var_dump($uri);


// ROUTING

if ('' == $uri[0]) {
    (new FrontController)->index();
}
elseif ('create' == $uri[0]) {
    (new FrontController)->create();
}
elseif ('store' == $uri[0]) {
    (new FrontController)->store();
}
elseif ('edit' == $uri[0]) {
    (new FrontController)->edit((int)$uri[1]);
}
elseif ('update' == $uri[0]) {
    (new FrontController)->update((int)$uri[1]);
}
elseif ('delete' == $uri[0]) {
    (new FrontController)->delete((int)$uri[1]);
}


echo 'DURYS';


