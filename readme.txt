=== Folder Slider ===
Contributors: vjalby
Tags: slider, slideshow, folder, bxslider
Requires at least: 3.5
Tested up to: 3.9.1
Stable tag: 1.0
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
* CSS (css): change the frame around slider: 'noborder', 'shadow', 'shadownoborder', 'black-border', 'white-border', 'gray-border'
* Width and Height of the slider (width and height)
* Speed (speed):  time between slides in seconds
* Previous/Next Buttons (controls): true or false
* Play/Pause Button (playcontrol): true or false
* Start Slider Automatically (autostart): true or false
* Pager (pager): true or false

Default slider width is 100% unless the attribute width is set to a non-zero value. The height is calculate for each picture (to keep ratio) unless the attribute height is set to a non-zero value.

Most of theses settings can be overridden using the corresponding shortcode attribute:

	[folderslider folder="wp-content/upload/MyPictures" width=500 
		mode=fade speed=2.5 captions=smartfilename controls=false css="gray-border"]
 
This plugin uses bxSlider 4.1.2 by Steven Wanderski - http://bxslider.com 

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

= 1.1b4 [2014-10-15] =
* New CSS style : CSS without border

= 1.1b3 [2014-10-04] =
* New CSS setting/option replacing CSS/Show box with shadow. 
* Misc changes

= 1.1b2 [2014-07-18] =
* When width option is set to 0, slider's width is set to the width of first picture (instead of 100 %)

= 1.1b1 [2014-07-05] =
* Update bxSlider to 4.1.2
* Speed (in seconds) may be decimal (e.g., 1.5)
* Shadow option can be set per slider. 

= 1.0 [2014-02-02] =
* Support for several sliders (with different settings) on the same page

= 0.94 [2013-08-22] =
* Safari bug fixed

= 0.92 [2013-08-20] =
* HTML tag bug fixed

= 0.91 [2013-08-19] =
* CSS bug fixed

= 0.9 [2013-08-19] =
* First released version