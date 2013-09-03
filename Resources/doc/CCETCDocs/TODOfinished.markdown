# CCETC/SonataAdminBundle - TODOfinished

## 1.2 - cleanup
1. <del>refactor and document Summary and Spreadsheet
2. <del>integrate with SonataUser
3. <del>improve configuration and document it
4. <del>check dependencies (jQuery, Boostrap, User Management) and and document them & set up requirements
	- <del>double check jQuery
	- <del>help route
	- <del>home route
	- <del>plural class labels
	- <del>document home/help routes and plural class labels

## 1.1
1.	New ideas and features lost from new admin layout
 - pre and post form and show hooks
 - hidden filters
 	- use array
	- Controller: check if any are set, and mark it
	- View: display afterwards, include link
	- js
	- clear hidden filters on less filters click
 - default filters
 - improve filter layout
	- use whole width
 - fix click event to hide/show filters
2.	Report Summary
 - table
	- build data
	- style better
	- sort?
	- fix resource table
 - customization
	- drop down for gb, bb, s, sf
	- more intuitive form
	- current values
	- default values
 - admin configuration
	- available fields for each
	- labels
	- field types
		- date
		- boolean
 -	clean list interface 
 -	clean up code
	- problems with code:
		- hard to generate spreadsheet from method results
			- make method that builds array
		- have to send fields to get method names
	- changes to make:
		- change breakByFields to xFields and groupByFields to yFields
		- general cleanup
			- variable/method names
			- more methods		
 -	excel export
	- get summary option values
3.	excel exporting
 - move admin arrays to admin class, use methods
 	- include field labels
 - add button to list
 - clean up list action
4.	Printing?
 - looks okay
10.	Misc
 - M2M plus button
 - relation links to view instead of edit
	- list
 - better breadcrumbs
 - save and create buttons
	- show item when it is created


## 1.0
0. new features
	- delete confirmation
1.	Transfer Customized Code
	- Admin.php
	- CRUDController.php
	- standard_layout.html.twig
	- base edit
	- base view
		- base view field
	- base list
	- dashboard
	- look in svn log
	- new code:
		- default filters
			- CRUDController and Admin
		- result count
			- adminextension / base_list => one liner
2.	Other bundles
	- User
		- links in header in standard_layout
	- Help
		- link in header
3.	Config
	- app name for title and header
	
	
## New Admin Transfer
 - edit/save/cancel interface
 - login, heading
 - List
 	- pagination
	- hidden filters?
		- turn off for now
	- labels
	- result count
 - breadcrumbs
 - form headings
 - don't hide filters if there are any?