<?php

namespace Sonata\AdminBundle\Scope;

class ScopeGroup {
    
    /**
     * class applied to ul ("pills" or "tabs")
     */
    protected $class;
    
    /**
     * label that appears before ul (optional)
     * @var string
     */
    protected $label;
    
    /**
     * Scopes to include in this group
     * @var array
     */
    protected $scopes;
    
    protected $datagrid;
    
    protected $datagridMapper;
    
    // groups that are not strong will retain active scopes for other groups    
    protected $strongGroup = false;
    
    /**
     *
     * @param string $class
     * @param string $label
     * @param array $scopes 
     */
    public function __construct($class, $datagrid, $datagridMapper, $scopes = null)
    {
        $this->class = $class;
        $this->scopes = $scopes;
        $this->datagrid = $datagrid;
        $this->datagridMapper = $datagridMapper;
        
        if(isset($scopes)) {
            foreach($scopes as $scope)
            {
                $scope->setGroup($this);
                if(!$this->datagrid->getFilter($scope->getField())) {
                    $this->datagridMapper->add($scope->getField(), null, array('isInvisible' => true));
                }
            }
        }
    }
    
    /**
     * Return the scope in this group that is active, if there is one
     * @return type 
     */
    public function getActiveScope()
    {
        foreach($this->scopes as $scope)
        {
            if($scope->isActive()) {
                return $scope;
            }
        }
        return null;
    }
    
    /**
     * Get an associative array of parameters that should be included in every link for this group.
     * @return type 
     */
    public function getParameters($additions = array())
    {
        $parameters = array();
        
        // groups that are not strong should retain active scopes for other groups
        if(!$this->strongGroup) {
            foreach($this->datagrid->getScopeGroups() as $group) {
                if($group != $this && $group->getActiveScope()) {
                    $activeScope = $group->getActiveScope();
                    $parameters['filter['.$activeScope->getField().'][value]'] = $activeScope->getValue();
                }
            }
        }
        
        foreach($additions as $k => $v) {
            $parameters[$k] = $v;
        }
        
        return $parameters;
    }
    
    
    public function setStrongGroup($bool)
    {
        $this->strongGroup = $bool;
    }
    
    public function getStrongGroup()
    {
        return $this->strongGroup;
    }
    
    public function getScopes()
    {
        return $this->scopes;
    }
    
    public function getDatagrid()
    {
        return $this->datagrid;
    }
    
    public function getLabel()
    {
        return $this->label;
    }
    public function setLabel($label)
    {
        $this->label = $label;
    }
    
    public function getClass()
    {
        return $this->class;
    }
    
  
}