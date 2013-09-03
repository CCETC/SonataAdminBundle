<?php
/**
 * Pat Haggerty <haggertypat@gmail.com>
 */

namespace Sonata\AdminBundle\Summary;

/**
 * A class for "summarizing" a set of objects by count or field value
 * and organizing the summary data for display.  
 */
class Summary
{

    /**
     * the admin class these summaries belong to
     * @var Admin
     */
    protected $admin;

    /**
     * container from the admin class these summaries belong to
     */
    protected $container;

    /**
     * The set of elements to summarize.
     * @var type 
     */
    protected $elements;

    /**
     * The method for summarized.
     * count = sums the total number of elements
     * sum = sums the values for $sumField
     * 
     * @var enum
     */
    protected $sumBy;

    /**
     * The field to summarize on when $sumBy = 'sum'
     * @var type 
     */
    protected $sumField;

    /**
     * the field to group $elements by along the y axis
     * @var type 
     */
    protected $yField;

    /**
     * the field to group $elements by along the x axis
     * @var type 
     */
    protected $xField;

    /**
     * The sums of counts or sums (determined by $sumBy and $sumField) for $elements,
     * grouped by those elements' $xField and $yField values in the following format:
     * 
     * array[yFieldName][xFieldName] = sum
     * 
     * @var array 
     */
    protected $summaries = array();

    /**
     * The sum of sums for the $xField values grouped in the following format:
     * 
     * array[xFieldName] = sum of sums
     * 
     * @var array
     */
    protected $xSummaries = array();

    /**
     * The sum of sums for the $xField values grouped in the following format:
     * 
     * array[xFieldName] = sum of sums
     * 
     * @var array
     */
    protected $ySummaries = array();

    /**
     * The total sum of counts or sums (determined by $sumBy and $sumField) for $elements
     * 
     * array[xFieldName] = sum of sums
     * 
     * @var integer
     */
    protected $grandTotal = 0;

    public function __construct($admin, $container, $yField, $xField, $sumBy, $sumField = null)
    {
        $this->admin = $admin;
        $this->container = $container;
        $this->yField = $yField;
        $this->xField = $xField;
        $this->sumBy = $sumBy;
        $this->sumField = $sumField;
    }

    /*
     * Summarize $elements by total elements ($sumBy = count)
     * or the sum of a specific field's value ($sumBy = sum && $sumField = 'fieldToSumBy')
     * 
     * Sums for each x/y pair are store in $summaries[y][x].
     * Sums of sums for each set of x values are stored in $xSummaries, and likewise for y values
     * 
     * You can group multiple fields together by setting a "other_field" attribute in the mapper.
     */

    public function buildSummaryDataFromElementSet($elements)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit ( 0 );

        $this->elements = $elements;

        foreach($elements as $e) {
            $xFieldsToCheck = array($this->xField);
            $yFieldsToCheck = array($this->yField);

            // we will aggregate sums for two fields if requested
            if(isset($this->admin->summaryXFields[$this->xField]['other_field'])) {
                $xFieldsToCheck[] = $this->admin->summaryXFields[$this->xField]['other_field'];
            }
            if(isset($this->admin->summaryYFields[$this->yField]['other_field'])) {
                $yFieldsToCheck[] = $this->admin->summaryYFields[$this->yField]['other_field'];
            }

            /*
             * build a grid of data - once cell for each x,y combination,
             * where x and y are unique values of xField and yField
             */
            foreach($xFieldsToCheck as $xFieldToCheck) {
                foreach($yFieldsToCheck as $yFieldToCheck) {
                    $yFieldValue = trim($this->getFieldValue($e, $this->admin->summaryYFields, $yFieldToCheck));
                    $xFieldValue = trim($this->getFieldValue($e, $this->admin->summaryXFields, $xFieldToCheck));

                    $this->initializeArrayKeys($yFieldValue, $xFieldValue);                    
                    
                    if($this->sumBy == 'count') {
                        $value = 1;
                    } else if($this->sumBy == 'sum') {
                        $sumFieldValue = $this->getFieldValue($e, $this->admin->summarySumFields, $this->sumField);
                        $value = $sumFieldValue;
                    }

                    $this->summaries[$yFieldValue][$xFieldValue] = $this->summaries[$yFieldValue][$xFieldValue] + $value;
                    $this->xSummaries[$xFieldValue] = $this->xSummaries[$xFieldValue] + $value;
                    $this->ySummaries[$yFieldValue] = $this->ySummaries[$yFieldValue] + $value;
                    $this->grandTotal = $this->grandTotal + $value;
                }
            }
        }
    }

    /**
     * Check through all three arrays to ensure that the values at keys
     * $yFieldValue and $xFieldValue are initialized.
     * 
     * @param string $yFieldValue
     * @param string $xFieldValue 
     */
    protected function initializeArrayKeys($yFieldValue, $xFieldValue)
    {
        if(!isset($this->summaries[$yFieldValue])) {
            $this->summaries[$yFieldValue] = array();
        }
        if(!isset($this->summaries[$yFieldValue][$xFieldValue])) {
            $this->summaries[$yFieldValue][$xFieldValue] = 0;
        }
        if(!isset($this->xSummaries[$xFieldValue])) {
            $this->xSummaries[$xFieldValue] = 0;
        }
        if(!isset($this->ySummaries[$yFieldValue])) {
            $this->ySummaries[$yFieldValue] = 0;
        }
    }

    /**
     * Get $element's value for $fieldName.
     * Check the field's definition to determine how to turn it into a string.
     * 
     * @param Object $element
     * @param array $fields
     * @param string $fieldName
     * @return string 
     */
    protected function getFieldValue($element, $fields, $fieldName)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit ( 0 );

        if(!isset($fields[$fieldName])) {
            // if an "other_field" it won't be in the $fields array
            return (string) $element[$fieldName];
        } else if($fields[$fieldName]['type'] == 'date') {
            if($element[$fieldName])
                return $element[$fieldName]->format('F j, Y');
            else
                return '';
        } else if($fields[$fieldName]['type'] == 'boolean') {
            if($element[$fieldName] == '1')
                return 'yes';
            else
                return 'no';
        } else if($fields[$fieldName]['type'] == 'relation') {
            $repository = $this->container->get('doctrine')->getRepository($fields[$fieldName]['relation_repository']);

            if(isset($element[$fields[$fieldName]['relation_field_name']])) {
                $object = $repository->findOneById($element[$fields[$fieldName]['relation_field_name']]);
                return (string) $object;
            } else {
                return '';
            }
        } else {

            return (string) $element[$fieldName];
        }
    }

    /**
     * Build and return an associative array with summary data, and some labels
     * 
     * Note: it is useful to have this here because this table is displayed with
     * different formatting in the twig template and the downloadable spreadsheet.
     */
    public function getTable()
    {
        ini_set('memory_limit', '1024M');
        set_time_limit ( 0 );

        $table = array();

        // generate header
        $header = array();

        // add a label for the yFields
        $header[] = $this->admin->summaryYFields[$this->yField]['label'];

        foreach($this->xSummaries as $xField => $values) {
            if(trim($xField) == "")
                $xLabel = "-";
            else
                $xLabel = $xField;
            $header[] = $xLabel;
        }
        $header[] = 'Total';

        $table[] = $header;

        // add body
        foreach($this->summaries as $yField => $xFields) {
            $bodyRow = array();
            // add a label for the $yField
            if(trim($yField) == "")
                $yLabel = "-";
            else
                $yLabel = $yField;

            $bodyRow[] = $yLabel;

            foreach($this->xSummaries as $xField => $values) {
                if(isset($xFields[$xField]))
                    $bodyRow[] = $xFields[$xField];
                else
                    $bodyRow[] = 0;
            }

            if(isset($this->ySummaries[$yField]))
                $bodyRow[] = $this->ySummaries[$yField];
            else
                $bodyRow[] = 0;

            $table[] = $bodyRow;
        }

        // add footer
        $footer = array('Total');

        foreach($this->xSummaries as $xField => $value) {
            $footer[] = $value;
        }
        $footer[] = $this->grandTotal;

        $table[] = $footer;

        return $table;
    }

    public function getSummaries()
    {
        return $this->summaries;
    }

    public function getXSummaries()
    {
        return $this->xSummaries;
    }

    public function getYSummaries()
    {
        return $this->ySummaries;
    }

    public function getGrandTotal()
    {
        return $this->grandTotal;
    }

    public function getYField()
    {
        return $this->yField;
    }

    public function getXField()
    {
        return $this->xField;
    }

    public function getSumField()
    {
        return $this->sumField;
    }

    public function getSumBy()
    {
        return $this->sumBy;
    }

    public function getParameters($additions)
    {
        $parameters = array('xField' => $this->xField, 'yField' => $this->yField);
        if(isset($this->sumField)) {
            $parameters['sumBy'] = $this->sumField;
        } else {
            $parameters['sumBy'] = $this->sumBy;
        }
        
        foreach($additions as $k => $v) {
            $parameters[$k] = $v;
        }

        return $parameters;
    }

}

