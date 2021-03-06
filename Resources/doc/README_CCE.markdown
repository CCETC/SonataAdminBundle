# CCETC/SonataAdminBundle - README

**NOTE: this bundle is not actively tested or maintained**

This bundle is a forked version of the [SonataAdminBundle](https://github.com/sonata-project/SonataAdminBundle).
It contains many customizations to the sonata-project bundle.

### Features
* approve/unapprove actions
* entity icons in breadcrumbs and on dashboard
* invisible filters
* extra filters ("more filters" button that adds some extra filters to the form)
* default filters
* pre/post template hooks for form/show fields and list/form/show templates
* custom field summary reports
* excel exporting for lists and summary reports
* TinyMCE integration
* expanded menu option
* improved acl handling on dashboard and menu
* custom action buttons (buttons in top right corner) for list/edit/show templates
* translations check the configured custom domain if nothing is found in the supplied domain
* option to hide show fields with empty values
* option to specifiy a service to be saved as the "appHelper" for admin_pool
* improved navigation

### Interface Changes
* Fewer submit buttons on edit
* several minor css changes
* simplified batch tools
* simplified breadcrumbs

# Installation
Install as a git submodule:

        git submodule add git://github.com/CCETC/SonataAdminBundle.git vendor/bundles/Sonata/AdminBundle

### Dependencies
CCETC/SonataAdminBundle requires the same dependencies as the sonata-admin/SonataAdminBundle (KnpMenu, JQuery).

This fork also requires CCETC forks of FOSUserBundle, SonataDoctrineORMAdminBundle, and SonataUserBundle.

- [CCETC/FOSUserBundle](https://github.com/CCETC/FOSUserBundle)
- [CCETC/SonataDoctrineORMAdminBundle](https://github.com/CCETC/SonataDoctrineORMAdminBundle)
- [CCETC/SonataUserBundle](https://github.com/CCETC/SonataUserbundle)

Additionally, the SonataAdminBundle requires [Twitter's Boostrap](http://twitter.github.com/bootstrap/).  The sonata-project fork includes Bootstrap, but since other CCETC bundles and projects require it, we have created a bundle as a container.  This fork of the admin bundle requires the CCETC/BoostrapBundle:

- [CCETC/CCETCBoostrapBundle](https://github.com/CCETC/CCETCBootstrapBundle)

Other dependencies:

- if using Spreadsheets: [PHPExcel](http://phpexcel.codeplex.com/) installed into vendors and added to autoload.php:

        $loader->registerPrefixes(array(
            'Twig_Extensions_' => __DIR__.'/../vendor/twig-extensions/lib',
            'Twig_'            => __DIR__.'/../vendor/twig/lib',
            'PHPExcel'         => __DIR__.'/../vendor/PHPExcel',
        ));

### Home and Help Routes
The twig base template uses ``home`` and ``adminHelp`` routes for the home and help links in the header.

### Entity Labels
The bundle makes use of singular and plural labels for each entity.  They are retrieved from ``Admin->getEntityLabel`` and ``Admin->getEntityLabelPlural``.  The labels should be set in each Admin class, but the defaults use the ``Admin->classnameLabel`` property.

# Documentation
All ISSUES, IDEAS, and FEATURES are documented on the [trello board](https://trello.com/board/sonataadminbundle/4f8f1c3859fe52017a1fc2e9).

# Pre/Post Template Hooks
You can include templates before or after any form or show field, before or after the form contents in base_edit.html.twig, the show table in base_show.html.twig or the list table in base_list.html.twig.

### Field Hooks
The Admin class has four arrays, $formFieldPreHooks/$formFieldPostHooks and $showFieldPreHooks/$showFieldPostHooks.

        public $formFieldPreHooks = array(
                'name' => 'MyBundle:myEntity:_myTemplate.html.twig'
        );
        
### Page Hooks
The edit, list, and show templates all include pre/post template hooks before and after the main content of the page.
The three variables used are $formPreHook, $showPreHook, and $listPreHook.  You can pass an optional ``parameters`` array and the contents will be sent to the template.

        public $formPreHook = array(
            'template' => 'MyBundle:myEntity:_myTemplate.html.twig';
            'parameters' => array('myVariable' => $myVariable)
        );

# Filters
We've added some features to the list filters.

## Invisible Filters
Use ``->add('myFilter', null, array('isInvisible' => true))`` to make the filter invisible to the user.

## Default Filters
You can define default values for any selection of filters.  These values will be used on every list request, provided no other value is set for that filter.

	$this->filterDefaults = array(
		'state' => 'NY'	
	);

## Scopes
For simple entites, improve browsing by adding scopes.  The list will use the scopes to add pills/tabs to the top of the page.  In ``configureDatagridFilters``, set your Scopes:

		$this->getDatagrid()->addScopeGroup(
				new ScopeGroup('pills', $this->getDatagrid(), $datagridMapper, array(
                    new Scope('approved', '1'),
                    new Scope('approved', '2', null, 'Unapproved'),
                )
        );		
		
The Scope constructor takes the following parameters:

 - $field
 - $value
 - $type (optional)
 - $label (default ucfirst($field))
		
Each scope takes a field/value pair that corresponds to a datagrid filter.  If a filter for the field in a scope does not exist, it will be created.  You can also set a label for a group.  Finally you can mark a group as a "strong" group.  If a group is not "strong" its links will not affect the selected scope of other groups.

        $tabs = new ScopeGroup('tabs', $this->getDatagrid(), $datagridMapper, array(
                    new Scope('beef', '1'),
                    new Scope('pork', '1'),
                    new Scope('rabbit', '1'),
                    new Scope('goat', '1'),
                    new Scope('lamb', '1'),
                )
        );
        
        $pills = new ScopeGroup('pills', $this->getDatagrid(), $datagridMapper, array(
                    new Scope('organic', '1'),
                    new Scope('organic', '2', '1', 'Non-organic'),
                )
        );
        $pills->setLabel("Type");
		$pills->setStrongGroup(true);
                
        $this->getDatagrid()->addScopeGroup($tabs);
        $this->getDatagrid()->addScopeGroup($pills);

# App Helper
You can define a service to be attached to the admin_pool for use in all of your templates:

    	sonata_admin:
            app_helper: myapp.bundle.dir.service

# Summary Reports
You can include a table of field summary statistics on the list template by defining a few fields to summarize by:

    use Sonata\AdminBundle\Summary\SummaryMapper;

	class AnimalAdmin extends Admin
	{
		protected function configureSummaryFields(SummaryMapper $summaryMapper)
		{
			$summaryMapper
				->addXField('species')
				->addXField('animalType', array('other_field' => 'animalTypeOther'))            
				->addYField('extinct', array('type' => 'boolean'))
				->addYField('Genus', array('type' => 'relation', 'relation_field_name' => 'Genus_id', 'relation_repository' => 'MyAnimalBundle:Genus'))
				->addSumField('numberOfLegs', array('label' => 'Number of Legs'))
			;
		}
    }

### X/Y Field Options
*type: boolean|date|relation(default: string)
*label: (default is uppercase fieldname)
*relation_repository: if the field is a relation, the repository of the related entity
*relation_field_name: if the field is a relation, the db field name of the relation field
*other_field: an additional field to aggregate the values of with the values of the defined field

### Sum Fields
By default, the total number of items for each group is summarized.  You can sum values of specific fields by adding "SumFields" using the addSumFields method.  This is entirely optional. 

# Spreadsheet Exporting
You can include buttons on the List template to download a xls spreadsheet of all elements that match the current filters.
        
    use Sonata\AdminBundle\Spreadsheet\SpreadsheetMapper;

	class AnimalAdmin extends Admin
	{
		protected function configureSpreadsheetFields(SpreadsheetMapper $spreadsheetMapper)
		{
			$spreadsheetMapper
				->add('species')
	            ->add('Genus', array('type' => 'relation', 'relation_field_name' => 'Genus_id', 'relation_repository' => 'MyAnimalBundle:Genus'))
				->add('extinct', array('type' => 'boolean'))
				->add('numberOfLegs', array('label' => 'Number of Legs'))
			;
		}
	}

### Spreadsheet Field Options
*type: boolean|date|relation(default: string)
*label: (default is uppercase fieldname)
*relation_repository: if the field is a relation, the repository of the related entity
*relation_field_name: if the field is a relation, the db field name of the relation field

# Navigation
We've made many changes to the way navigation in the bundle works.

* the old breadcrumbs have been removed
* action buttons (new, list, edit, delete) now appear as tabs, and are configured within the admin class through the ``getActionMenuItems`` and ``getObjectActionMenuItems``

## Action Menus
There are two menus - ``getActionMenuItems`` and ``getObjectActionMenuItems``.  The former appears when an object is not defined, and the later when one is.

Each tab has three possible values:

* label
* icon (class given to i tag)
* href
* class (class given to li)

### Customizing Action Menus

    public function getActionMenuItems($action, $object = null)
    {
        $items = parent::getActionMenuItems($action, $object);
        
        // if just one tab, don't show any
        if(count($items) == 1) {
            return array();
        } else {
            return $items;
        }
    }

    public function getActionMenuItems($action, $object = null)
    {
        $items = parent::getActionMenuItems($action, $object);
        
        // remove list tab
        unset($items['list']);
        
        // add a tab
        $items['myNotifications'] = array(
            'label' => 'My Notifications',
            'icon' => 'icon-signal',
            'href' => $this->generateUrl('myNotifications')
        );
        if($action == 'myNotifications') $items['myNotifications']['class'] = 'active';
        
        return $items;
    }



# Hiding Empty Show Fields
You can optionally have the Show template exclude fields that are null for the object.
In your Admin class, add the following to enable:

	public $hideEmptyShowFields = true;
	
To always show some fields, regardless of their value, include a black list:

    public $hideableShowFieldBlacklist = array (
        'field1',
        'field2',
        'field3',
    );
    

# Configuration
We have added one additional configuration option that toggles between a dropdown menu and an expanded menu:

	sonata_admin:
            expanded_menu: true
