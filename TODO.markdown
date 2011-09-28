CCETC/SonataAdminBundle - TODO
=========================================

1.0
=========================================
	
//# 0. new features

//	delete confirmation

//# 1.	Transfer Customized Code
	
//	Admin.php
//	CRUDController.php
//	standard_layout.html.twig
//	base edit
//	base view
//		base view field
//	base list
//	...
//		dashboard

//	- look in svn log

//	- new code:
	
//		- default filters
		
//			CRUDController and Admin

//		- result count
		
//			adminextension / base_list => one liner
	
//# 2.	Other bundles

//	User
//		links in header in standard_layout
	
//	Help
//		link in header
	
	
//# 3.	Config

//	- app name for title and header

2.0
=========================================

#	1.	lost features	

	- lead events in base_view

		- pre and post hooks

	- many to one link in base_view_field
	
	- speed boost with label style in base_view_field -> used to be in controller, but don't want to have to add each view field to the custom group array
	
	- hide some entities on dashboard
	
	- hide Create link in base_list.html.twig sometimes
		-> find a way to define which admins should not have this
		-> now checks that the route is defined?
		- would like to disable create/edit for some entities / some users

