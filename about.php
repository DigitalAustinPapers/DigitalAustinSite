<?php require_once 'src/TemplateRenderer.class.php';

// Include any logic for this page below


$renderer = new TemplateRenderer();
// Include any variables as an array in the second param
print $renderer->render('about.html.twig');