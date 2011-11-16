<?php

namespace Sonata\AdminBundle\Spreadsheet;

class SpreadsheetMapper {
   
   protected $admin;
   
   public function __construct( $admin)
    {
        $this->admin = $admin;
    }

    
   public function add($fieldName, $options = array())
   {
       $this->admin->spreadsheetFields[$fieldName] = $this->getField($fieldName, $options);
       return $this;
   }   
   
   protected function getField($fieldName, $options)
   {
       $field = array();
       
       if(isset($options['label'])) $field['label'] = $options['label'];
       else $field['label'] = ucfirst($fieldName);

       if(isset($options['type'])) $field['type'] = $options['type'];
       else $field['type'] = 'string';

       return $field;       
   }
}

