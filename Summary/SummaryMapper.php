<?php

namespace Sonata\AdminBundle\Summary;

class SummaryMapper
{

    protected $admin;

    public function __construct($admin)
    {
        $this->admin = $admin;
    }

    public function addYField($fieldName, $options = array())
    {
        $this->admin->summaryYFields[$fieldName] = $this->getField($fieldName, $options);
        return $this;
    }

    public function addXField($fieldName, $options = array())
    {
        $this->admin->summaryXFields[$fieldName] = $this->getField($fieldName, $options);
        return $this;
    }

    public function addSumField($fieldName, $options = array())
    {
        $this->admin->summarySumFields[$fieldName] = $this->getField($fieldName, $options);
        return $this;
    }

    protected function getField($fieldName, $options)
    {
        $field = array();

        if(isset($options['label']))
            $field['label'] = $options['label'];
        else
            $field['label'] = ucfirst($fieldName);

        if(isset($options['type']))
            $field['type'] = $options['type'];
        else
            $field['type'] = 'string';

        if(isset($options['repository']))
            $field['repository'] = $options['repository'];

        if(isset($options['field_name']))
            $field['field_name'] = $options['field_name'];

        if(isset($options['other_field']))
            $field['other_field'] = $options['other_field'];

        return $field;
    }

}

