<?php

namespace Sonata\AdminBundle\Summary;

class SummaryMapper {
   
   protected $admin;
   
   public function __construct( $admin)
    {
        $this->admin = $admin;
    }

    
   public function addYfield($fieldName, $options = array())
   {
       $this->admin->summaryYFields[$fieldName] = $this->getField($fieldName, $options);
       return $this;
   }
   
   public function addXfield($fieldName, $options = array())
   {
       $this->admin->summaryXFields[$fieldName] = $this->getField($fieldName, $options);
       return $this;
   }
   
   public function addSum($fieldName, $options = array())
   {
       $this->admin->summarySumFields[$fieldName] = $this->getField($fieldName, $options);
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

