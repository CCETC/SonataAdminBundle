# CCETC/SonataAdminBundle - IDEAS

1.	hooks
	- always wrap showFieldHooks in table cells
	- always wrap formFieldHooks in appropriate div
2.	summary
	- summary units labels
		- default "items"
		- use plural entity if available...
		- require noun for custom fields?
	- use guesser for field types
3.	excel downloads
	- write cron to clear directory every day
	- use guesser for field types
4.	better implementation of boolean groups
	- doesn't work with summary
	- The messiest piece of this is the display of form fields and show fields.  Each of these fields and groups of fields has a set of pre/post hooks that give the groups labels, and indent the fields.  This is particularly confusing for the first field of each group, since in needs two pre hooks.
5.  Trash
	- simply add field to trashable items
	- on default, delete actions are not shown, trash are instead
	- add link to view the trash that uses a filter to filter results
		- when filter is on, use delete actions ONLY	
6.	Testing
	- learn how to run original test to ensure customizations have not broken anything
	- write tests for custom code
7.	Minimize dependencies
	- should not depend upon FOSUser
	- should not depend upon SonataDoctrineORMAdminBundle
