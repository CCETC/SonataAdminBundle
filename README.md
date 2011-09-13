CCETC/SonataAdminBundle
============

This bundle is a forked version of the SonataAdminBundle.
It contains many customizations to the sonata-project bundle.
This bundle is used in all CCETC web applications.

# Features
* approve/unapprove actions
* show field labels and formatting (indenting, grouping boolean fields)
* form field formatting (indenting, grouping boolean fields)
* entity icons in breadcrumbs and on dashboard
* hidden filters

# Interface Changes
* Fewer submit buttons on edit
* several minor css changes (filter box on top, form and show styles_
* simplified batch tools
* simplified breadcrumbs
* heading on show

# Installation
Install as a git submodule:

        git submodule add git://github.com/CCETC/SonataAdminBundle.git vendor/bundles/CCETC/SonataAdminBundle

Pull updates from the CCETC/SonataAdminBundle:

        git pull origin

TODO: document installation

- help route?
- configuration

## Dependencies
CCETC/SonataAdminBundle requires the same dependencies as the sonata-admin/SonataAdminBundle (KnpMenu, Blueprint, Jquery).

CCETC specific dependencies are:

- [CCETC fork of FOSUserBundle](https://github.com/CCETC/FOSUserBundle)
- [CCETCErrorReportBundle] (https://github.com/CCETC/CCETCErrorReportBundle).

# Development
From any Symfony application with CCETC/SonataAdminBundle installed, you can make changes to the bundle and 
push them to the GitHub repository.


Before pushing, add upstream remotes to your checked-out submodule and pull upstream changes from the sonata-project:
        
        git remote add upstream git://githb.com/sonata-project/SonataAdminBundle.git
        git pull upstream master

# New Features
TODO: document use of new features