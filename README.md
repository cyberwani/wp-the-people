## Questions
* Does the data for a petition change at any given point?
* Would it be a wise idea to open commenting on a petition through Disqus? I feel like politics can get out of control very quickly. It would be an awesome idea to bring to the table though.

## Potential Ideas
This is just a compilation of the thoughts I've had while brainstorming about the actual plugin itself.

* Widget that pulls in imported petition
    * Widget should be customizable
        * Should have options to show body, title, button, etc.
        * Would be cool to select different views for the widget that allowed the frontend display to change
        * Easily templateable
    * Displays a bar showing the latest progress of the petition. Cache
* Imported Petitions, and their relevant data, should be stored to posts
* Share button for the widget itself - Twitter, Facebook, Google Plus, Share This, etc.
* Real time refresh via AJAX so we can get updates for this petition. This should only query and update the cache on the site if it has expired.
* Address race conditions on data refresh - very normal thing to handle
* When a petition is "imported", we should grab the signature data as well. Once we have the signature data cached, we can record the last date it was synced and what the current offset was at that time, etc. It should resume intuitively so there is minimal work for the end user in updating petitions and their attached signatures.
    * Signature data is a key thing to our application as we can do many things with it, such as enable the ability to show graphs based on this data.
    * Signature data should not be saved from raw JSON but rather compressed to save bytes. Compress in a similar format: CD|33414,JJ|90210 etc.
    * Might be helpful to use local cache to store the signature data while caching it
    * We know the total count so we can linearly update the data and show a progress bar with the results.
* It might be a good thing to make use of a modal for the front-end widget.
* Shortcode can be tweaked with various attributes to change how it's embedded into the page
    * Can embed a chart of activities for a specific petition. Maybe even the ability to specify when the chart starts and/or ends, etc.
    * Can embed various tools that make it easy to include petitions into their website inline and such.
    * Example 1: `[petition type="geochart" height="300" width="500"][/petition]`
    * Example 2: `[petition type="timeline"][/petition]`
* Modal to demonstrate history behind a Petition
    * Tabular interface that shows computed data sets on a graph
    * Signature Trends over time
    * Geographic Trends
    * Chronological Timeline of things
        * scrolling bar that allows you to horizontally scroll through the progress of this petition - similar to weather.com forecasts