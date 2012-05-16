<?php

namespace Sonata\AdminBundle\Scope;

class Scope {
    
    /**
     * field the scope is for
     * @var string
     */
    protected $field;
    
    /**
     * filter value corresponding to the $field
     * @var mixed
     */
    protected $value;
    
    /**
     * label used in li
     * @var string
     */
    protected $label;
    
    protected $group;
    
    public function __construct($field, $value, $label = null)
    {
        $this->field = $field;
        $this->value = $value;
        $this->label = $label;
    }
    
    public function isActive()
    {
        $filterValues = $this->group->getDatagrid()->getValues();
                
        return isset($filterValues[$this->field]) && $filterValues[$this->field]['value'] == $this->value;
    }
    
    /**
     * Get the parameters to include in this scope's link
     * @return associative array 
     */
    public function getParameters()
    {
        $parameters = $this->group->getParameters();
        
        $parameters['filter['.$this->field.'][value]'] = $this->value;
        
        return $parameters;
    }
        
    public function setGroup($group)
    {
        $this->group = $group;
    }

    public function getField()
    {
        return $this->field;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getLabel()
    {
        if(isset($this->label)) return $this->label;
        else return ucfirst($this->field);
    }
    
}