CCETC/SonataAdminBundle - TODO
=========================================

Issues
=============================
	
	- relation not displayed on Show 
		
		- see Features Resources
		
		- link to view instead of edit
		
	- TH / TD spacing on show


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

	
1.1
=========================================

//	1.	New ideas and features lost from new admin layout

//		- pre and post form and show hooks
						
//		- hidden filters
		
//			- use array
			
//			- Controller: check if any are set, and mark it
			
//			- View: display afterwards, include link
			
//			- js

//			- clear hidden filters on less filters click
	
//		- default filters
	
//		- improve filter layout
		
//			- use whole width
		
//		- fix click event to hide/show filters


	2.	Report Summary
	
//		a. table
	
//			- build data
	
//			- style better
		
//			- sort?

//			- fix resource table
		
//		b. customization
		
//			- drop down for gb, bb, s, sf

//			- more intuitive form

//			- current values
			
//			- default values
		
//		c. admin configuration
		
//			- available fields for each
			
//			- labels
		
//			- field types
			
//				- date
				
//				- boolean
				
		
		d.	clean list interface
			
//		e.	clean up code
		
//			- problems with code:
				
//				- hard to generate spreadsheet from method results
			
//					- make method that builds array
	
//				- have to send fields to get method names
			
			
//			- changes to make:
			
//				- change breakByFields to xFields and groupByFields to yFields

//				- general cleanup
				
//					- variable/method names
					
//					- more methods
				
				
			
//		f.	excel export
		
//			- get summary option values


//	3.	excel exporting
	
//		- move admin arrays to admin class, use methods
			
//			- include field labels
	
//		- add button to list

//		- clean up list action
		
	
//	4.	Printing?

//		- looks okay
		

//	10.	Misc
	
//		- M2M plus button
		
//		- relation links to view instead of edit
		
//			- list
			
		
//		- better breadcrumbs
		
//		- save and create buttons
		
//			- show item when it is created
