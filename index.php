<?php require_once 'src/TemplateRenderer.class.php';

// Include any logic for this page below


$template = new TemplateRenderer();
// Include any variables as an array in the second param
print $template->render('index.html.twig');