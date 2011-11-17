CCETC/SonataAdminBundle
============

This bundle is a forked version of the SonataAdminBundle.
It contains many customizations to the sonata-project bundle.
This bundle is used in all CCETC web applications.

### Features
* approve/unapprove actions
* entity icons in breadcrumbs and on dashboard
* hidden filters
* default filters
* pre/post template hooks for form/show fields and list/form/show templates
* custom field summary reports
* excel exporting for lists and summary reports

### Interface Changes
* Fewer submit buttons on edit
* several minor css changes
* simplified batch tools
* simplified breadcrumbs

# Installation
Install as a git submodule:

        git submodule add git://github.com/CCETC/SonataAdminBundle.git vendor/bundles/Sonata/AdminBundle

In config.yml, specific the application name and admin e-mail twice:

        services
            adminSettings:
              class: Sonata\AdminBundle\Resources\config\Settings
              arguments: [Get Your GreenBack - Tompkins!, haggertypat@gmail.com]
        
        twig:
            globals:
              adminTitle: "Get Your GreenBack - Tompkins!"
              adminEmail: "haggertypat@gmail.com"
        

### Dependencies
CCETC/SonataAdminBundle requires the same dependencies as the sonata-admin/SonataAdminBundle (KnpMenu, JQuery).

CCETC specific dependencies are:

- [CCETC/FOSUserBundle](https://github.com/CCETC/FOSUserBundle)
- [CCETCErrorReportBundle] (https://github.com/CCETC/CCETCErrorReportBundle)
- [CCETC/SonataDoctrineORMAdminBundle] (https://github.com/CCETC/SonataDoctrineORMAdminBundle)

Other dependencies:

- if using Spreadsheets: [PHPExcel](http://phpexcel.codeplex.com/) installed into vendors and added to autoload.php:

        $loader->registerPrefixes(array(
            'Twig_Extensions_' => __DIR__.'/../vendor/twig-extensions/lib',
            'Twig_'            => __DIR__.'/../vendor/twig/lib',
            'PHPExcel'         => __DIR__.'/../vendor/PHPExcel',
        ));


# Updating
Pull updates from the CCETC/SonataAdminBundle:

        git pull origin

Add upstream remote and pull updates from Sonata/SonataAdminBundle

        git remote add upstream git://github.com/sonata-project/SonataadminBundle.git
        git pull upstream master


# Development
From any Symfony application with CCETC/SonataAdminBundle installed, you can make changes to the bundle and 
push them to the GitHub repository.


Before pushing, add upstream remotes to your checked-out submodule and pull upstream changes from the sonata-project:
        
        git remote add upstream git://githb.com/sonata-project/SonataAdminBundle.git
        git pull upstream master

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

    protected function configureSummaryFields(SummaryMapper $summaryMapper)
    {
        $summaryMapper
            ->addXField('title')
            ->addYField('category')
            ->addYField('approved', array('type' => 'boolean'))
            ->addSumField('stepCount', array('label' => 'Step Count'))
        ;
    }

### Options
*type: boolean|date|(string)
*label: (default is uppercase fieldname)

### Sum Fields
By default, the total number of items for each group is summarized.  You can sum values of specific fields by adding "SumFields" using the addSumFields method.  This is entirely optional. 

# Spreadsheet Exporting
You can include buttons on the List template to download a xls spreadsheet of all elements that match the current filters.
        
    use Sonata\AdminBundle\Spreadsheet\SpreadsheetMapper;

    protected function configureSpreadsheetFields(SpreadsheetMapper $spreadsheetMapper)
    {
        $spreadsheetMapper
            ->add('title')
            ->add('category')
            ->add('approved', array('type' => 'boolean'))
            ->add('stepCount', array('label' => 'Step Count'))
        ;
    }


# Configuration
TODO: document configuration
