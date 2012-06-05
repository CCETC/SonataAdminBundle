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
     * filter type corresponding to the $field
     * @var mixed
     */
    protected $type;
    
    /**
     * label used in li
     * @var string
     */
    protected $label;
    
    protected $group;
    
    public function __construct($field, $value, $type = null, $label = null)
    {
        $this->field = $field;
        $this->value = $value;
        $this->label = $label;
        $this->type = $type;
    }
    
    public function isActive()
    {
        $filterValues = $this->group->getDatagrid()->getValues();
    
        $filterAndValueMatch = isset($filterValues[$this->field]) && $filterValues[$this->field]['value'] == $this->value;
        
        if(isset($this->type)) {
            return $filterAndValueMatch && $filterValues[$this->field]['type'] == $this->type;
        } else {
            return $filterAndValueMatch;
        }
    }
    
    /**
     * Get the parameters to include in this scope's link.
     * The parameters should include the following:
     *  - the filter defined by this scope
     *  - any parameters in $additions
     *  - any parameters applicable to the whole group
     * 
     * @return associative array 
     */
    public function getParameters($additions = array())
    {
        $parameters = $this->group->getParameters($additions);

        $parameters['filter['.$this->field.'][value]'] = $this->value;
        
        if(isset($this->type)) {
            $parameters['filter['.$this->field.'][type]'] = $this->type;
        }
        
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

    public function getType()
    {
        return $this->type;
    }

    public function getLabel()
    {
        if(isset($this->label)) return $this->label;
        else return ucfirst($this->field);
    }
    
}