<?php

namespace Sonata\AdminBundle\Summary;

class Summary {
    /**
     * the admin class these summaries belong to
     * @var Admin
     */
    protected $admin;
    
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
    
    public function __construct($admin, $yField, $xField, $sumBy, $sumField = null)
    {
        $this->admin = $admin;
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
     */
    public function buildSummaryDataFromElementSet($elements)
    {
        $this->elements = $elements;
        
        foreach($elements as $e)
        {
            $yFieldValue = trim($this->getFieldValue($e, $this->admin->summaryYFields, $this->yField));
            $xFieldValue = trim($this->getFieldValue($e, $this->admin->summaryXFields, $this->xField));
            
            if($this->sumBy == 'sum') $sumFieldKey = $this->getFieldValue($e, $this->admin->summarySumFields, $this->sumField);
                
            $this->initializeArrayKeys($yFieldValue, $xFieldValue);
            
            if($this->sumBy == 'count')
            {
                $value = 1;
            }
            else if($this->sumBy == 'sum')
            {
                $value = $sumFieldKey;
            }
            
            $this->summaries[$yFieldValue][$xFieldValue] = $this->summaries[$yFieldValue][$xFieldValue] + $value;               
            $this->xSummaries[$xFieldValue] = $this->xSummaries[$xFieldValue] + $value;
            $this->ySummaries[$yFieldValue] = $this->ySummaries[$yFieldValue] + $value;
            $this->grandTotal = $this->grandTotal + $value;
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
        if(!isset($this->summaries[$yFieldValue]))
        {
            $this->summaries[$yFieldValue] = array();
        }
        if(!isset($this->summaries[$yFieldValue][$xFieldValue]))
        {
            $this->summaries[$yFieldValue][$xFieldValue] = 0;                
        }
        if(!isset($this->xSummaries[$xFieldValue]))
        {
            $this->xSummaries[$xFieldValue] = 0;
        }
        if(!isset($this->ySummaries[$yFieldValue]))
        {
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
        if($fields[$fieldName]['type'] == 'date')
        {
            if($element[$fieldName])
                return $element[$fieldName]->format('F j, Y');
            else
                return '';
        }
        else if($fields[$fieldName]['type'] == 'boolean')
        {
            if($element[$fieldName] == '1') return 'yes';
            else return 'no';
        }
        else
        {
            return (string) $element[$fieldName];        
        }
    }
    
    /**
     * Get an associative array that represents the table values for the summary table
     */
    public function getTable()
    {
        $table = array();
        
        // generate header
        $header = array();
        
        // add a label for the yFields
        $header[] = $this->admin->summaryYFields[$this->yField]['label'];

        foreach($this->xSummaries as $xField => $values)
        {
            if(trim($xField) == "") $xLabel = "-";
            else $xLabel = $xField;
            $header[] = $xLabel;
        }
        $header[] = 'Total';
        
        $table[] = $header;
        
        // add body
        foreach($this->summaries as $yField => $xFields)
        {
            $bodyRow = array();
            // add a label for the $yField
            if(trim($yField) == "") $yLabel = "-";
            else $yLabel = $yField;
            
            $bodyRow[] = $yLabel;
            
            foreach($this->xSummaries as $xField => $values)
            {
                if(isset($xFields[$xField])) $bodyRow[] = $xFields[$xField];
                else $bodyRow[] = 0;
            }
            
            if(isset($this->ySummaries[$yField])) $bodyRow[] = $this->ySummaries[$yField];
            else $bodyRow[] = 0;

            $table[] = $bodyRow;
        }
        
        // add footer
        $footer = array('Total');
        
        foreach($this->xSummaries as $xField => $value)
        {
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
        if(isset($this->sumField)) $parameters['sumBy'] = $this->sumField;
        else $parameters['sumBy'] = $this->sumBy;
        
        foreach($additions as $k => $v)
        {
            $parameters[$k] = $v;
        }
        
        return $parameters;
    }
}

