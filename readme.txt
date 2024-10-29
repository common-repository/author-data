=== Plugin Name ===
Contributors: rudydrimar
Donate link: 
Tags: authors, manage
Requires at least: 2.7.1
Tested up to: 2.8
Stable tag: 3.1

Creates and manages a table with several data from the authors of a blog. It allows to list this data in various formats

== Description ==

This plugin allows you to create and manage a table with several data from the authors (if there is more than one) of the blog, such as place and date of birth, personal website and a brief biographical detail.

It have two separate parts:

1. The Management panell. There you can add, modify or view the data from one or all the authors of the blog.
2. The Options panell. There you can customize the visualization of the existing data.

== Installation ==

1. Upload `author-data` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place `<?php author_data(); ?>` in your templates to see the information (you can also place `<?php author_link(); ?>` to view only a list of authors with a link to their personal webs).
4. Or you can place `[autores-ficha]` or `[autores-links]` to do the same in a post or page.
5. The plugin creates its own Menu in your Admin page.

== Frequently Asked Questions ==


== New Features in 3.0 release ==

* Total integration with tinyMCE wysiwyg editor for biographical data

== Screenshots ==

1. Full list of authors (in a page)
2. Full list of personal webs by the authors (in the sidebar)
3. Manage Panell
4. Options Panell