# CCETC/SonataAdminBundle - README

This bundle is a forked version of the [SonataAdminBundle](https://github.com/sonata-project/SonataAdminBundle).
It contains many customizations to the sonata-project bundle.

### Features
* approve/unapprove actions
* entity icons in breadcrumbs and on dashboard
* hidden filters
* default filters
* pre/post template hooks for form/show fields and list/form/show templates
* custom field summary reports
* excel exporting for lists and summary reports
* TinyMCE integration
* expanded menu option
* improved acl handling on dashboard and menu

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

This fork also requires CCETC forks of FOSUserBundle, SonataDoctrineORMAdminBundle, and SonataUserBundle.  It may work fine as is, or with small modifications, but all CCETC web applications use these bundles.

- [CCETC/FOSUserBundle](https://github.com/CCETC/FOSUserBundle)
- [CCETC/SonataDoctrineORMAdminBundle] (https://github.com/CCETC/SonataDoctrineORMAdminBundle)
- [CCETC/SonataUserBundle](https://github.com/CCETC/SonataUserbundle)

Other dependencies:

- if using Spreadsheets: [PHPExcel](http://phpexcel.codeplex.com/) installed into vendors and added to autoload.php:

        $loader->registerPrefixes(array(
            'Twig_Extensions_' => __DIR__.'/../vendor/twig-extensions/lib',
            'Twig_'            => __DIR__.'/../vendor/twig/lib',
            'PHPExcel'         => __DIR__.'/../vendor/PHPExcel',
        ));

# Pre/Post Template Hooks
You can include templates before or after any form or show field, before or after the form contents in base_edit.html.twig, the show table in base_show.html.twig or the list table in base_list.html.twig.

### Field Hooks
The Admin class has four arrays, $formFieldPreHooks/$formFieldPostHooks and $showFieldPreHooks/$showFieldPostHooks.

        public $formFieldPreHooks = array(
                'name' => 'MyBundle:myEntity:_myTemplate.html.twig'
        );
        
### Page Hooks
The edit, list, and show templates all include pre/post template hooks before and after the main content of the page.
The three variables used ar $formPreHook, $showPreHook, and $listPreHook.

        public $formPreHook = 'MyBundle:myEntity:_myTemplate.html.twig';

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


# Configuration
We have added one additional configuration option that toggles between a dropdown menu and an expanded menu:

	sonata_admin:
    	expanded_menu: true
