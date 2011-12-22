CCETC/SonataAdminBundle - TODO
=========================================

Roll Out
====================

	- check error report usePageHeader

Issues
=============================
	
	- relation not displayed on Show 
		
		- see Features Resources
		
		- link to view instead of edit
		
	- TH / TD spacing on show


1.3
=======================================
	
    - refactor and document Summary and Spreadsheet

    1.  Trash

        - simply add field to trashable items

        - on default, delete actions are not shown, trash are instead

        - add link to view the trash that uses a filter to filter results

            - when filter is on, use delete actions ONLY


Ideas
=========================================

	a.	hooks

		- always wrap showFieldHooks in table cells
		
		- always wrap formFieldHooks in appropriate div

	b.	summary
	
		- summary units
		
			- default "items"

				- use plural entity if available...
				
					- worth doing
			
			- require noun for custom fields
			
		- use guesser
		

	c.	excel downloads
	
		- write cron to clear directory every day

		- use guesser
		
		
	d.	better implementation of boolean groups
	
		- big mess right now
		
		- doesn't work with summary