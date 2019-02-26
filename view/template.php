<?php

class Template {
  private $_scriptPath = TEMPLATE_PATH;
  public $properties;



  public function setScriptPath($scriptPath){
    $this->_scriptPath=$scriptPath;
  }

  public function __construct(){
      $this->properties = array();
  }

  public function render($filename){
    
   ob_start();
   if(file_exists($this->_scriptPath.$filename)){
      include($this->_scriptPath.$filename);
    } else throw new TemplateNotFoundException();
    return ob_get_clean();
  }

  public function __set($k, $v){
      $this->properties[$k] = $v;
  }

  public function __get($k){
      return $this->properties[$k];
  }
}
?>