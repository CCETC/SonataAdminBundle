<?php
/**
 * Pat Haggerty <haggertypat@gmail.com>
 */

namespace Sonata\AdminBundle\Summary;

/**
 * A class for building lists of fields of an Entity that can be used to summarize
 * objects of that Entity.
 */
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

        if(isset($options['relation_repository']))
            $field['relation_repository'] = $options['relation_repository'];

        if(isset($options['relation_field_name']))
            $field['relation_field_name'] = $options['relation_field_name'];

        if(isset($options['other_field']))
            $field['other_field'] = $options['other_field'];

        return $field;
    }

}

