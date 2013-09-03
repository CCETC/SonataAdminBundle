<?php
/**
 * Pat Haggerty <haggertypat@gmail.com>
 */

namespace Sonata\AdminBundle\Spreadsheet;

/**
 * A Class for building and saving spreadsheets of SonataAdmin data using PHPExcel.
 */
class Spreadsheet
{

    protected $admin;
    protected $container;

    public function __construct($admin, $container)
    {
        $this->admin = $admin;
        $this->container = $container;
    }

    public function returnCSV($array)
    {
        ob_start(); // buffer the output ...
        $fp = fopen('php://output', 'w'); // this file actual writes to php output

        foreach($array as $subArray) {
            fputcsv($fp, $subArray);
        }

        fclose($fp);
        return ob_get_clean(); // ... then return it as a string!
    }
     
    /**
     * Generate and display CSV data from a list of objects.
     * Only save the fields specified by the spreadsheetFields array in the Admin class.
     * 
     * @param type $objects
     * @return string 
     */
    public function generateListCSV($objects)
    {
        ini_set('memory_limit', '4000M');
        set_time_limit ( 0 );
        
        $csv = array();

        // add headings
        $headings = array();
        foreach($this->admin->spreadsheetFields as $f => $values) {
            $headings[] = $values['label'];
        }

        $csv[] = $headings;

        // add a row for each object
        foreach($objects as $o) {
            $row = array();

            foreach($this->admin->spreadsheetFields as $f => $values) {
                $value = $this->getFieldValue($o, $values, $f);
                $row[] = $value;
            }

            $csv[] = $row;
        }

        return $this->returnCSV($csv);
    }

    /**
     * Get $element's value for $fieldName.
     * Check the field's definition to determine how to turn it into a string.
     * 
     * @param type $element
     * @param type $keys the attributes for $fieldName as defined in Admin->spreadsheetFields
     * @param type $fieldName
     * @return type 
     */
    protected function getFieldValue($element, $keys, $fieldName)
    {
        ini_set('memory_limit', '4000M');
        set_time_limit ( 0 );

        if($keys['type'] == 'date') {
            if($element[$fieldName])
                return $element[$fieldName]->format('F j, Y');
            else
                return '';
        } else if($keys['type'] == 'boolean') {
            if($element[$fieldName] == '1')
                return 'yes';
            else
                return 'no';
        } else if($keys['type'] == 'relation') {
            $repository = $this->container->get('doctrine')->getRepository($keys['relation_repository']);
            
            if(isset($element[$keys['relation_field_name']])) {
                $object = $repository->findOneById($element[$keys['relation_field_name']]);
                return (string) $object;
            } else {
                return '';
            }            
        } else {
            return (string) $element[$fieldName];
        }
    }

    /**
     * Use PHP Excel to build and save a spreadsheet of data from summary data build by the Summary class.
     *
     * @param type $summary
     * @return string 
     */
    public function buildAndSaveSummarySpreadsheet($summary)
    {
        ini_set('memory_limit', '4000M');
        set_time_limit ( 0 );
        
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
        foreach($summary->getTable() as $columns) {
            $x = 0;
            foreach($columns as $column) {
                $value = $column;

                $objPHPExcel->getActiveSheet()->SetCellValue($this::num2alpha($x) . $y, $value);
                $x++;
            }

            $y++;
        }


        $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
        $filename = 'downloads/' . str_replace(' ', '_', $this->admin->getEntityLabelPlural()) . '_Summary_' . rand(500, 999) . '.xls';
        $objWriter->save($filename);

        return $filename;
    }

    /*
     * get the alpa representation of an integer, 2 => B, 27 => AA
     */
    protected static function num2alpha($n)
    {
        for($r = ""; $n >= 0; $n = intval($n / 26) - 1)
            $r = chr($n % 26 + 0x41) . $r;
        return $r;
    }

}