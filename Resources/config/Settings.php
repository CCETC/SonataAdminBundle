<?php

namespace Sonata\AdminBundle\Resources\config;

class Settings {
  public $adminTitle;
  
  public function __construct($adminTitle) {
    $this->adminTitle = $adminTitle;    
  }
}