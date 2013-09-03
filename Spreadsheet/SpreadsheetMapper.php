<?php
/**
 * Pat Haggerty <haggertypat@gmail.com>
 */

namespace Sonata\AdminBundle\Spreadsheet;

/**
 * A class for building lists of fields of an Entity that the application programer
 * wants to be included in list spreadsheets.
 * 
 */
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

       if(isset($options['relation_repository'])) $field['relation_repository'] = $options['relation_repository'];

       if(isset($options['relation_field_name'])) $field['relation_field_name'] = $options['relation_field_name'];

       return $field;       
   }
}

