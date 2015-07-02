<?php require_once 'src/TemplateRenderer.class.php';
// TODO: This needs to be moved out of the document root

$search_results = $_POST['basicData'];

$template = new TemplateRenderer();
// Include any variables as an array in the second param
print $template->render('search_result.twig', array(
                        'search_results' => $search_results,
));