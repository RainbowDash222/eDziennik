<?php
class View
{
  private $variables = array();
  private $file = null;

  public function __construct($name)
  {
    $this->file = __DIR__ . '/includes/' . strtolower($name) . '.php';
    if (!file_exists($this->file)) {
      die("File " . $this->file . " does not exist!");
    }
  }

  public function allocate($variable, $value)
  {
    $this->variables[$variable] = $value;
  }

  public function render()
  {
    extract($this->variables);
    include($this->file);
  }
}
