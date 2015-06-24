<?php
// Ref: http://stackoverflow.com/questions/9842342/building-a-site-with-twig-php-template-engine
// autoload file from Composer to load and register Twig
require_once 'vendor/autoload.php';

class TemplateRenderer
{
  public $loader;  // Instance of Twig_loader_Filesystem
  public $environment;  // Instance of Twig_Environment

  // Option to pass additional env and template parameters
  public function __construct($envOptions = array(), $templateDirs = array())
  {

    // Merge optional params with default env params
    $envOptions += array(
      'debug' => false,
      'charset' => 'utf-8',
      'cache' => 'src/templates/compilation_cache',
      'auto_reload' => true,
    );

    $templateDirs = array_merge(
      array('src/templates')  // Default template directory
    );

    // Twig loader
    $this->loader = new Twig_Loader_Filesystem($templateDirs);
    // Twig environment
    $this->environment = new Twig_Environment($this->loader, $envOptions);
  }

  public function render($templateFile, array $variables=array()) {
    return $this->environment->render($templateFile, $variables);
  }
}