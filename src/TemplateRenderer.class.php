<?php
// Ref: http://stackoverflow.com/questions/9842342/building-a-site-with-twig-php-template-engine
// autoload file from Composer to load and register Twig
require_once 'vendor/autoload.php';

class TemplateRenderer {

  protected $template_dir = 'src/templates/';
  protected $vars = array();
  public $loader;  // Instance of Twig_loader_Filesystem
  public $environment;  // Instance of Twig_Environment

  // Option to pass additional env parameters
  public function __construct($envOptions = array()) {

    // Merge optional params with default env params
    $envOptions += array(
      'debug' => false,
      'charset' => 'utf-8',
      'cache' => $this->template_dir.'compilation_cache',
      'auto_reload' => true,
    );

    // Twig loader
    $this->loader = new Twig_Loader_Filesystem($this->template_dir);
    // Twig environment
    $this->environment = new Twig_Environment($this->loader, $envOptions);
  }

  public function render($template_file, array $variables=array()) {
    if (file_exists($this->template_dir.$template_file)) {
      return $this->environment->render($template_file, $variables);
    } else {
      throw new Exception('No template file ' . $template_file . ' present in
      directory ' . $this->template_dir);
    }
  }
}