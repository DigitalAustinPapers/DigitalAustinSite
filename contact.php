<?php
require_once 'src/TemplateRenderer.class.php';
$template = new TemplateRenderer();

// Include any logic for this page below



// Variables should be passed to the template with $template->var = vars
// before rendering
print $template->render('contact.html.twig');