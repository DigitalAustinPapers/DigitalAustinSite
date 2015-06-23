<?php require_once 'vendor/autoload.php';

$loader = new Twig_Loader_Filesystem('src/templates');

$twig = new Twig_Environment($loader, array(
  'cache' => 'src/templates/compilation_cache',
));

//echo $twig->render('index.html', array());
