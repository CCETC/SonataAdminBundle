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


Next
================
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
	
		- may be to slow if we are retrieving all objects every time
		
			- could move to separate page...


		- summary units
		
			- default "items"

				- use plural entity if available...
				
					- worth doing
			
			- require noun for custom fields
			
		- use guesser


	c.	excel downloads
	
		- write cron to clear directory every day

		- use guesser