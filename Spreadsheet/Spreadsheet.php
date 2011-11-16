<?php

namespace Sonata\AdminBundle\Spreadsheet;

class Spreadsheet {
    
    protected $admin;
    
    public function __construct($admin)
    {
        $this->admin = $admin;
   
    }
    
    public function buildAndSaveListSpreadsheet($objects)
    {
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator($this->admin->getEntityLabelPlural());
        $objPHPExcel->getProperties()->setLastModifiedBy($this->admin->getEntityLabelPlural());
        $objPHPExcel->getProperties()->setTitle($this->admin->getEntityLabelPlural());
        $objPHPExcel->getProperties()->setSubject($this->admin->getEntityLabelPlural());
        $objPHPExcel->getProperties()->setDescription($this->admin->getEntityLabelPlural());

        $objPHPExcel->setActiveSheetIndex(0);            

        // add headings
        $col = 0;
        foreach($this->admin->spreadsheetFields as $f => $values)
        {
            $objPHPExcel->getActiveSheet()->SetCellValue($this::num2alpha($col) . '1', $values['label']);
            $col++;
        }

        // add a row for each object
        $i = 2;
        foreach($objects as $o)
        {
            $col = 0;
            foreach($this->admin->spreadsheetFields as $f => $values)
            {
                $value = $this->getFieldValue($o, $values, $f);

                $objPHPExcel->getActiveSheet()->SetCellValue($this::num2alpha($col) . $i, $value);
                $col++;
            }

            $i++;
        }

        
        $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
        $filename = 'downloads/'.str_replace(' ', '_', $this->admin->getEntityLabelPlural()).rand(500,999).'.xls';
        $objWriter->save($filename);
        
        return $filename;
    }
    
    protected function getFieldValue($element, $keys, $fieldName)
    {
        $methodName = 'get'.ucfirst($fieldName);
        
        if($keys['type'] == 'date')
        {
            return $element->$methodName()->format('F j, Y');
        }
        else if($keys['type'] == 'boolean')
        {
            if($element->$methodName() == '1') return 'yes';
            else return 'no';
        }
        else
        {
            return (string) $element->$methodName();        
        }
    }
    
    /*
     * get the alpa representation of an integer, 2 => B, 27 => AA
     */
    protected static function num2alpha($n)
    {
        for($r = ""; $n >= 0; $n = intval($n / 26) - 1)
            $r = chr($n%26 + 0x41) . $r;
        return $r;
    }

   
}