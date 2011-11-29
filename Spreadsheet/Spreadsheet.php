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
        // cache the spreadsheet, because they get big fast and cause fatal memory errors
        // the cache_to_discISAM method is slowest but most effective, and the only we could get to work
        $cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_to_discISAM;
        \PHPExcel_Settings::setCacheStorageMethod($cacheMethod);

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
        $filename = 'downloads/'.str_replace(' ', '_', $this->admin->getEntityLabelPlural()).'_List_'.rand(500,999).'.xls';
        $objWriter->save($filename);
        
        return $filename;
    }
    
    protected function getFieldValue($element, $keys, $fieldName)
    {
        if($keys['type'] == 'date')
        {
            if($element[$fieldName])
                return $element[$fieldName]->format('F j, Y');
            else
                return '';
        }
        else if($keys['type'] == 'boolean')
        {
            if($element[$fieldName] == '1') return 'yes';
            else return 'no';
        }
        else
        {
            return (string) $element[$fieldName];        
        }
    }
    
    
    public function buildAndSaveSummarySpreadsheet($summary)
    {
         // cache the spreadsheet, because they get big fast and cause fatal memory errors
        // the cache_to_discISAM method is slowest but most effective, and the only we could get to work
        $cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_to_discISAM;
        \PHPExcel_Settings::setCacheStorageMethod($cacheMethod);
        
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator($this->admin->getEntityLabelPlural());
        $objPHPExcel->getProperties()->setLastModifiedBy($this->admin->getEntityLabelPlural());
        $objPHPExcel->getProperties()->setTitle($this->admin->getEntityLabelPlural());
        $objPHPExcel->getProperties()->setSubject($this->admin->getEntityLabelPlural());
        $objPHPExcel->getProperties()->setDescription($this->admin->getEntityLabelPlural());

        $objPHPExcel->setActiveSheetIndex(0);            

        $y = 1;
        foreach($summary->getTable() as $columns)
        {
            $x = 0;
            foreach($columns as $column)
            {
                $value = $column;
                
                $objPHPExcel->getActiveSheet()->SetCellValue($this::num2alpha($x) . $y, $value);
                $x++;
            }

            $y++;
        }

        
        $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
        $filename = 'downloads/'.str_replace(' ', '_', $this->admin->getEntityLabelPlural()).'_Summary_'.rand(500,999).'.xls';
        $objWriter->save($filename);
        
        return $filename;
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