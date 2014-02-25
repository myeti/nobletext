<?php

ini_set("session.use_trans_sid", "false");

require 'vendor/autoload.php';


/**
 * Config
 */
define('NB_USERNAME', 'Babor');
define('NB_PASSWORD', '3f6f6d7c89d3c8b71750424d1ffc3c481ac351c5');
define('NB_ROOT', __DIR__ . '/public/notes/');


/**
 * Routing
 */
$app = Craft\Web\App::forge([

    '/'             => 'My\Logic\Editor::index',
    '/new'          => 'My\Logic\Editor::create',
    '/write/:id'    => 'My\Logic\Editor::write',
    '/delete/:id'   => 'My\Logic\Editor::delete',

    '/login'        => 'My\Logic\Front::login',
    '/logout'       => 'My\Logic\Front::logout',

    '/404'         => 'My\Logic\Error::lost',

]);


/**
 * App settings
 */
$app->on(404, function() use($app) {
    $app->plug('/404');
});

$app->on(403, function() use($app) {
    $app->plug('/login');
});


/**
 * Let's go !
 */
$app->plug();