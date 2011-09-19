<?php

namespace Sonata\AdminBundle\Resources\config;

class Settings {
  public $adminTitle;
  public $adminEmail;
  
  public function __construct($adminTitle, $adminEmail) {
    $this->adminTitle = $adminTitle;
    $this->adminEmail = $adminEmail;
  }
}