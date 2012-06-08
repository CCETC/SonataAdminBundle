<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\AdminBundle\Admin;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Pool
{
    protected $container = null;

    protected $adminServiceIds = array();

    protected $adminGroups = array();

    protected $adminClasses = array();

    protected $templates    = array();

    protected $title;

    protected $titleLogo;
    
    protected $expandedMenu;

    protected $appHelper;
    
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param string $title
     * @param string $logoTitle
     */
    public function __construct(ContainerInterface $container, $title, $logoTitle, $expandedMenu, $appHelper = null)
    {
        $this->container  = $container;
        $this->title      = $title;
        $this->titleLogo  = $logoTitle;
        $this->expandedMenu = $expandedMenu;
        $this->appHelper = $this->container->get($appHelper);
    }

    /**
     * @return array
     */
    public function getGroups()
    {
        $groups = $this->adminGroups;

        foreach ($this->adminGroups as $name => $adminGroup) {
            foreach ($adminGroup as $id => $options) {
                $groups[$name][$id] = $this->getInstance($id);
            }
        }

        return $groups;
    }

    public function getGroupByAdminCode($adminCode)
    {
        $groups = $this->adminGroups;

        foreach ($this->adminGroups as $name => $adminGroup) {
            foreach ($adminGroup as $id => $options) {
                if($id == "items") {
                    foreach($options as $option)
                    {
                        if($option == $adminCode) {
                            return $adminGroup;
                        }
                    }
                }
            }
        }
        
        return $groups;
    }

    /**
     * @return array
     */
    public function getDashboardGroups()
    {
        $groups = $this->adminGroups;

        foreach ($this->adminGroups as $name => $adminGroup) {
            if (isset($adminGroup['items'])) {
                foreach ($adminGroup['items'] as $key => $id) {
                    $admin = $this->getInstance($id);

                    if ($admin->showIn(Admin::CONTEXT_DASHBOARD)) {
                        $groups[$name]['items'][$key] = $admin;
                    } else {
                        unset($groups[$name]['items'][$key]);
                    }
                }
            }

            if (empty($groups[$name]['items'])) {
                unset($groups[$name]);
            }
        }

        return $groups;
    }
    
    

    /**
     * return the admin related to the given $class
     *
     * @param string $class
     * @return \Sonata\AdminBundle\Admin\AdminInterface|null
     */
    public function getAdminByClass($class)
    {
        if (!$this->hasAdminByClass($class)) {
            return null;
        }

        return $this->getInstance($this->adminClasses[$class]);
    }

    /**
     * @param $class
     * @return bool
     */
    public function hasAdminByClass($class)
    {
        return isset($this->adminClasses[$class]);
    }

    /**
     * Returns an admin class by its Admin code
     * ie : sonata.news.admin.post|sonata.news.admin.comment => return the child class of post
     *
     * @param string $adminCode
     * @return \Sonata\AdminBundle\Admin\AdminInterface|null
     */
    public function getAdminByAdminCode($adminCode)
    {
        $codes = explode('|', $adminCode);
        $admin = false;
        foreach ($codes as $code) {
            if ($admin == false) {
                $admin = $this->getInstance($code);
            } else if ($admin->hasChild($code)) {
                $admin = $admin->getChild($code);
            }
        }

        return $admin;
    }

    /**
     * Returns a new admin instance depends on the given code
     *
     * @param $id
     * @return \Sonata\AdminBundle\Admin\AdminInterface
     */
    public function getInstance($id)
    {
        return $this->container->get($id);
    }

    /**
     * @return null|\Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param array $adminGroups
     * @return void
     */
    public function setAdminGroups(array $adminGroups)
    {
        $this->adminGroups = $adminGroups;
    }

    /**
     * @return array
     */
    public function getAdminGroups()
    {
        return $this->adminGroups;
    }

    /**
     * @param array $adminServiceIds
     * @return void
     */
    public function setAdminServiceIds(array $adminServiceIds)
    {
        $this->adminServiceIds = $adminServiceIds;
    }

    /**
     * @return array
     */
    public function getAdminServiceIds()
    {
        return $this->adminServiceIds;
    }

    /**
     * @param array $adminClasses
     * @return void
     */
    public function setAdminClasses(array $adminClasses)
    {
        $this->adminClasses = $adminClasses;
    }

    /**
     * @return array
     */
    public function getAdminClasses()
    {
        return $this->adminClasses;
    }

    /**
     * @param array $templates
     * @return void
     */
    public function setTemplates(array $templates)
    {
        $this->templates = $templates;
    }

    /**
     * @return array
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * @param $name
     * @return null|string
     */
    public function getTemplate($name)
    {
        if (isset($this->templates[$name])) {
            return $this->templates[$name];
        }

        return null;
    }

    /**
     * @return string
     */
    public function getTitleLogo()
    {
        return $this->titleLogo;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * @return boolean
     */
    public function getExpandedMenu()
    {
        return $this->expandedMenu;
    }    
    
    public function getAppHelper()
    {
        return $this->appHelper;
    }
    
    public function getGroupMenu($admin)
    {
        $adminGroup = $this->getGroupByAdminCode($admin->getCode());
        
        $items = array();
        
        foreach($adminGroup['items'] as $item)
        {
            $itemAdmin = $this->getInstance($item);
            if($itemAdmin->isGranted('LIST')) {
                $items[] = array(
                    'label' => $itemAdmin->getEntityLabelPlural(),
                    'url' => $itemAdmin->generateUrl('list'),
                    'active' => $admin->getCode() == $item
                );
            }
        }
        
        return $items;
      /*  {% set adminGroup = admin_pool.getGroupByAdminCode(admin.code) %}
                <ul class="tabs">
                {% for groupMember in adminGroup.items %}
                    {% set groupMemberAdmin = admin_pool.getInstance(groupMember) %}
                    {% if groupMemberAdmin.isGranted('LIST') %}    
                    <li {{ admin.code == groupMemberAdmin.code ? 'class="active"' : ''}}>
                        <a href="{{ groupMemberAdmin.generateUrl('list') }}">{{ groupMemberAdmin.entityLabelPlural }}</a>
                    </li>
                    {% endif %}
                {% endfor %}
                </ul>
                
                
                 {% for item in admin_pool.getGroupMenu(admin) %}
                    <li {{ item.active ? 'class="active"' : ''}}>
                        <a href="{{ item.url }}">{{ item.label }}</a>
                    </li>
                {% endfor %}*/
                
                
    }
}