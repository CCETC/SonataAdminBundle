<?php
namespace Sonata\AdminBundle\Resources;

use Symfony\Components\Templating\Helper\Helper;

class AdminHelper extends Helper
{
    public function getAdminTitle()
    {
        return 'git it';
    }
}