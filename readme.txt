=== Folder Slider ===
Contributors: vjalby
Tags: slider, slideshow, folder, bxslider
Requires at least: 3.5
Tested up to: 3.6
Stable tag: 0.94
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin generates picture sliders from a folder using a shortcode.

== Description ==

This plugin creates picture sliders (slideshows) from a folder.
The pictures folder must be uploaded (using FTP) somewhere on the server (e.g. wp-content/upload).

To include a slider in a post or a page, you have to use the following shortcode :

	[folderslider folder="local_path_to_folder"]

An Options page allow to set the default paramaters of the sliders :

* Transition Mode (mode): horizontal, vertical, fade
* Caption Format (captions): none, filename, filenamewithoutextension, smartfilename (filename with underscores, extension and front numbers removed)
* Width and Height of the slider (width and height)
* Speed (speed):  time between slides in seconds
* Previous/Next Buttons (controls): true or false
* Play/Pause Button (playcontrol): true or false
* Start Slider Automatically (autostart): true or false
* Pager (pager): true or false
* CSS/Show box with shadow: global option to enable/disable the box around the slider

Default slider width is 100% unless the attribute width is set to a non-zero value. The height is calculate for each picture (to keep ratio) unless the attribute height is set to a non-zero value.

Most of theses settings can be overridden using the corresponding shortcode attribute:

	[folderslider folder="wp-content/upload/MyPictures" width=500 
		mode=fade speed=2 captions=smartfilename controls=false]
 
This plugin uses bxSlider 4.1.1 by Steven Wanderski - http://bxslider.com 

Sample, contact available at http://jalby.org/wordpress/

== Installation ==

1. Unzip the archive folder-slider.zip
2. Upload the directory 'folder-slider' to the '/wp-content/plugins/' directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Upload a folder of pictures to 'wp-content/upload/MyPictures'
5. Insert the following short code in post or page :

	[folderslider folder="wp-content/upload/MyPictures"]

== Screenshots ==
1. Folder Slider Settings
2. Folder Slider in a post

== Changelog ==

= 0.95 [2013-09-08] =
* Support for several sliders (with different settings) on the same page

= 0.94 [2013-08-22] =
* Safari bug fixed

= 0.92 [2013-08-20] =
* HTML tag bug fixed

= 0.91 [2013-08-19] =
* CSS bug fixed

= 0.9 [2013-08-19] =
* First released version