<?php

namespace Sonata\AdminBundle\Show;

/**
 * A class to handle the hiding of empty Show fields.
 * This feature is turned on/off by Admin->$hideEmptyShowFields.
 * If on, fields listed in Admin->$hideableShowFieldBlacklist are always showed.
 * If $showAllFields is true, all fields are shown.
 */
class HideableShowFields
{
    protected $admin;
    protected $object;
    protected $showAllFields;

    public function __construct($admin, $object, $showAllFields)
    {
        $this->admin = $admin;
        $this->object = $object;
        $this->showAllFields = $showAllFields;
    }

    
    public function processHiddenShowFields()
    {
        $showGroups = $this->admin->getShowGroups();
        $descriptions = $this->admin->getShowFieldDescriptions();
        
        foreach($showGroups as $name => $showGroup) {
            $groupHasData = false;
            
            foreach($showGroup['fields'] as $fieldName) {
                if($this->fieldShouldBeDisplayed($fieldName)) {
                    $descriptions[$fieldName]->setOption('displayField', true);
                    $groupHasData = true;
                } else {
                    $descriptions[$fieldName]->setOption('displayField', false);
                }
            }
            
            $displayGroup = $this->groupShouldBeDisplayed($groupHasData);
            $showGroups[$name]['displayGroup'] = $displayGroup;
        }
        
        $this->admin->setShowGroups($showGroups);
    }
    
    private function groupShouldBeDisplayed($groupHasData)
    {
        return !$this->admin->hideEmptyShowFields || $this->showAllFields || $groupHasData;
    }
    
    private function fieldShouldBeDisplayed($fieldName)
    {
        $method = 'get'.$fieldName;
        return !$this->admin->hideEmptyShowFields || $this->showAllFields || in_array($fieldName, $this->admin->hideableShowFieldBlacklist) ||  $this->object->$method();
    }
}