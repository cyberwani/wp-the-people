## General

## Questions
* Does the data for a petition change at any given point?

## Todo
* Images need to be run through an optimizer
* Comment JavaScript
* Caching needs to be added to the server

## Potential Ideas
This is just a compilation of the thoughts I've had while brainstorming about the actual plugin itself.

* Import all petition data using the intermediary API we've built so we can enhance queries altogether.
	* Query for 3 things:
	    * Petition Data
	    * Geographic data
	    * Timeline data
	* Save the queried data to the content of the post.
* Implement share this button for the embedded petition.
* Shortcode handles how the petition is embedded into the post. For each petition we need to offer 3 "petition views". ( Possibly more, if we have time )
	* Geographic chart that shows how many votes came from the specified areas - `[petition id="" type="geographic" height="300" width="500" /]`
	* Timeline that can will show how fast the petition advanced. - `[petition id="" type="timeline" /]`
	* Basic view that shows the petition's details - `[petition id="" /]`
	* ( Optional ) Line chart that shows the activity of the petition over days, weeks, and months.
* Petition views need to be hookable and easily stylized.
* Perhaps, if we have time, we can implement color pickers for various things like map color, etc.