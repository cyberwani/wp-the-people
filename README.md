## Questions
* Does the data for a petition change at any given point?

## Potential Ideas
* Commenting
    * Come up with some way to create a single comment thread so all blog's that use this plugin can view the same discussion and comment on the same thread.
* Rating Petitions - same thing as above
* Analytics tracking system that allows us to track which websites are most popular for a variety of things: comments, signatures, ratings, etc.
* Widget to live search for Petitions
    * Widget should be customizable
        * Should have options to show body, title, button, etc.
        * Would be cool to select different views for the widget that allowed the frontend display to change
    * When the live search is pulled in, we should attach the data that probably will not change to a post-type.
    * Displays a bar showing the latest progress of the petition. Cache
* Share button for the widget itself - Twitter, Facebook, Google Plus, Share This, etc.
* Real time refresh interval
* cURL multi degrade to WP remote get
* address race conditions on data refresh
* Get signature data in the backend when a petition is selected by the admin - cache it and keep track of new changes.
    * This will help us generate maps and other data on demand for the petition with the data on demand
    * May be use local storage to cache the data while downloading it?
* Modal to demonstrate history behind a Petition
    * Tabular interface that shows computed data sets on a graph
    * Signature Trends
    * Geographic
    * Chronological Timeline of things
        * scrolling bar that allows you to horizontally scroll through the progress of this petition - similar to weather.com forecasts
* Embed a petition into any post via a short code.