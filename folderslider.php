<?php
/*
Plugin Name: Folder Slider
Version: 1.1b3
Plugin URI: http://www.jalby.org/wordpress/
Author: Vincent Jalby
Author URI: http://www.jalby.org
Description: This plugin creates picture slider from a folder. The slider is generated in a post or page with a shortcode. Usage: [folderslider folder="local_path_to_folder"].
Tags: slider, slideshow, folder, bxslider
Requires: 3.5
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/*  Copyright 2013  Vincent Jalby  (wordpress /at/ jalby /dot/ org)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


new folderslider();

class folderslider{

	private $slider_no = 0;
	
	function folderslider() {		
		add_action( 'admin_menu', array( $this, 'fsd_menu' ) );	
		add_action( 'admin_init', array( $this, 'fsd_settings_init' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'fsd_styles' ) );
		add_shortcode( 'folderslider', array( $this, 'fsd_slider' ) );
		add_action('plugins_loaded', array( $this, 'fsd_init' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'fsd_plugin_action_links' ) );
	}

	function fsd_init() {
		load_plugin_textdomain( 'folderslider', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		if ( !is_admin() ) add_filter( 'widget_text', array( $this, 'fsd_widget_shortcode' ), 11 );
		$fsd_options = get_option( 'FolderSlider' );
		if ( empty( $fsd_options ) ) {
			update_option( 'FolderSlider', $this->fsd_settings_default() );
		}
	}
	
	function fsd_widget_shortcode( $content ) {
		if ( false === stripos( $content, '[folderslider' ) ) {
			return $content;
		} else {
			return do_shortcode( $content );
		}
	}
	
	function fsd_styles() {
		wp_enqueue_style( 'bxslider-style', plugins_url( 'jquery.bxslider/jquery.bxslider.css', __FILE__ ) );
		wp_enqueue_style( 'fsd-style', plugins_url( 'style.css', __FILE__ ) );
	}

	function fsd_scripts( $param, $num ) {
		wp_enqueue_script( 'bxslider-script', plugins_url( 'jquery.bxslider/jquery.bxslider.min.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'fsd_slider-script', plugins_url( 'slider.js', __FILE__ ), array( 'jquery' ) );
		wp_localize_script( 'fsd_slider-script', 'FSDparam' . $num , $param );
	}

	function file_array( $directory ) { // List all JPG & PNG files in $directory
		$files = array();
		if( $handle = opendir( $directory ) ) {
			while ( false !== ( $file = readdir( $handle ) ) ) {
				$ext = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );
				if ( 'jpg' == $ext || 'png' == $ext ) {
					$files[] = $file;
				}
			}
			closedir( $handle );
		}
		sort( $files );
		return $files;
	}

	function filename_without_extension ( $filename ) {
		$info = pathinfo($filename);
		return basename($filename,'.'.$info['extension']);
	}

	function smartfilename ( $filename ) {
		$filename = $this->filename_without_extension ( $filename );
		$filename = preg_replace ( '/^\d+/' , '' , $filename );
		$filename = str_replace( '_', ' ', $filename );
		return $filename;
	}
					
	function fsd_slider( $atts ) { // Generate slider
		$fsd_options = get_option( 'FolderSlider' );
		extract( shortcode_atts( array(
			'folder'  => 'wp-content/uploads/',
			'width'   => $fsd_options['width'],
			'height'  => $fsd_options['height'],
			'mode'    => $fsd_options['mode'],
			'controls' => $fsd_options['controls'],
			'autostart' => $fsd_options['autostart'],
			'playcontrol' => $fsd_options['playcontrol'],
			'speed' => $fsd_options['speed'],
			'captions' => $fsd_options['captions'],
			'pager' => $fsd_options['pager'],
			'css'=> $fsd_options['css'],
		), $atts ) );

		$folder = rtrim( $folder, '/' ); // Remove trailing / from path

		if ( !is_dir( $folder ) ) {
			return '<p style="color:red;"><strong>Folder Slider Error: </strong>Unable to find the directory ' . $folder . '</p>' ;
		}
	
		$pictures = $this->file_array( $folder );
		$NoP = count( $pictures );

		if ( 0 == $NoP ) {
			return '<p style="color:red;"><strong>Folder Slider Error: </strong>No picture available inside '. $folder . '</p>';
		}
		
		//Calculate Slider's Width from first picture
		if ( $width == 0 ) {
			$image = wp_get_image_editor( $folder . '/' . $pictures[ 0 ] );
			if ( ! is_wp_error( $image ) ) {
				$size = $image->get_size();
				$width = $size['width'];
			}
		}
		// Set JS parameters
		++$this->slider_no;
		$param = array( 'width'=>$width, 'controls'=>($controls == 'true'), 'auto'=>($autostart == 'true'), 'playcontrol'=>($playcontrol == 'true'), 'speed'=>intval($speed*1000), 'captions'=>($captions != 'none'), 'pager'=>($pager == 'true'), 'mode'=>$mode );
		$this->fsd_scripts($param, $this->slider_no);

		$picture_size = "";
		if ( $width > 0)  $picture_size = " width=\"$width\"";
		if ( $height > 0)  $picture_size .= " height=\"$height\"";

		switch ( $css ) {
			case 'noborder':
				$slider_code = '<div class="bx-wrapper-noborder">'. "\n";
			break;
			case 'shadow':
				$slider_code = '<div>'. "\n";
			break;
			case 'black-border':
				$slider_code = '<div class="bx-wrapper-border-black">'. "\n";
			break;
			case 'white-border':
				$slider_code = '<div class="bx-wrapper-border-white">'. "\n";
			break;
			case 'gray-border':
				$slider_code = '<div class="bx-wrapper-border-gray">'. "\n";
			break;	
		}
		
		$slider_code .= '<ul class="bxslider bxslider' . $this->slider_no . '">';
		
		for ( $idx = 0 ; $idx < $NoP ; $idx++ ) {
			switch ( $captions ) {
				case 'filename':
					$title = $pictures[ $idx ];
					break;
				case 'filenamewithoutextension':
					$title = $this->filename_without_extension( $pictures[ $idx ] );
					break;
				case 'smartfilename':
					$title = $this->smartfilename( $pictures[ $idx ] );
					break;
				default:
					$title = '';
				break;
			}	
			$slider_code .= '<li><img src="' . home_url( '/' ) . $folder . '/' . $pictures[ $idx ] . '"';
			$slider_code .= $picture_size;
			if ( $title ) {
				$slider_code .= " title=\"$title\"";
				$slider_code .= " alt=\"$title\"";
			} else {
				$slider_code .= ' alt="' . $pictures[ $idx ] . '"' ;
			}
			$slider_code .= " /></li>\n";
		}
		
		$slider_code .= "</ul>\n</div>\n";
		
		return $slider_code;
	}

/* --------- Folder Slider Settings --------- */

	function fsd_settings_default() {
		$defaults = array(
			'width'   => 0,
			'height'  => 0,
			'mode'    => 'horizontal',
			'controls' => true,
			'playcontrol' => true,
			'autostart' => true,
			'speed' => 3,
			'captions' => 'none',
			'pager' => true,
			'css' => 'shadow',
		);
		return $defaults;
	}

	function fsd_menu() {
		add_options_page( 'Folder Slider Settings', 'Folder Slider', 'manage_options', 'folder-slider', array( $this, 'fsd_settings' ) );
	}

	function fsd_settings_init() {
		register_setting( 'FolderSlider', 'FolderSlider', array( $this, 'fsd_settings_validate' ) );
	}

	function fsd_plugin_action_links( $links ) { 
 		// Add a link to this plugin's settings page
 		$settings_link = '<a href="' . admin_url( 'options-general.php?page=folder-slider' ) . '">' . __('Settings') . '</a>';
 		array_unshift( $links, $settings_link ); 
 		return $links; 
	}

	function fsd_settings_validate( $input ) {
		$input['width']  = intval( $input['width'] );
		$input['height'] = intval( $input['height'] );
		if ( ! in_array( $input['mode'], array( 'horizontal','vertical','fade' ) ) ) $input['mode'] = 'horizontal';
		if ( ! in_array( $input['captions'], array( 'none','filename','filenamewithoutextension','smartfilename' ) ) ) $input['captions'] = 'none';
		if ( ! in_array( $input['css'], array( 'noborder','shadow','black-border','white-border','gray-border' ) ) ) $input['css'] = 'noborder';
		$input['speed']          = floatval( $input['speed'] );
		if ( 0 == $input['speed'] ) $input['speed'] = 5;
		$input['controls'] = ( 1 == $input['controls'] );
		$input['playcontrol'] = ( 1 == $input['playcontrol'] );
		$input['autostart'] = ( 1 == $input['autostart'] );
		$input['pager'] = ( 1 == $input['pager'] );
		return $input;
	}

	function fsd_option_field( $field, $label, $extra = 'px' ) {
		$fsd_options = get_option( 'FolderSlider' );
		echo '<tr valign="top">' . "\n";
		echo '<th scope="row"><label for="' . $field . '">' . $label . "</label></th>\n";
		echo '<td><input id="' . $field . '" name="FolderSlider[' . $field . ']" type="text" value="' . $fsd_options["$field"] . '" class="small-text"> ' . $extra . "</td>\n";
		echo "</tr>\n";
	}

	function fsd_settings()
	{
		$fsd_options = get_option( 'FolderSlider' );
		echo '<div class="wrap">' . "\n";
		screen_icon();
		echo '<h2>' . __( 'Folder Slider Settings', 'folderslider' ) . "</h2>\n";
		echo '<form method="post" action="options.php">' . "\n";
		settings_fields( 'FolderSlider' );
		echo "\n" . '<table class="form-table"><tbody>' . "\n";
		
		// Transition Mode
		echo '<tr valign="top">' . "\n";
		echo '<th scope="row"><label for="mode">' . __( 'Transition Mode', 'folderslider' ) . "</label></th>\n";
		echo '<td><select name="FolderSlider[mode]" id="FolderSlider[mode]">' . "\n";		
			echo "\t" .	'<option value="horizontal"';
				if ( 'horizontal' == $fsd_options['mode'] ) echo ' selected="selected"';
				echo '>' . __('Horizontal', 'folderslider') . "</option>\n";
			echo "\t" .	'<option value="vertical"';
				if ( 'vertical' == $fsd_options['mode'] ) echo ' selected="selected"';
				echo '>' . __('Vertical', 'folderslider') . "</option>\n";
			echo "\t" .	'<option value="fade"';
				if ( 'fade' == $fsd_options['mode'] ) echo ' selected="selected"';
				echo '>' . __('Fade', 'folderslider') . "</option>\n";
		echo "</select>\n";
		echo "</td>\n</tr>\n";

		// Captions
		echo '<tr valign="top">' . "\n";
		echo '<th scope="row"><label for="captions">' . __( 'Caption Format', 'folderslider' ) . '</label></th>' . "\n";
		echo '<td><select name="FolderSlider[captions]" id="FolderSlider[captions]">' . "\n";		
			echo "\t" .	'<option value="none"';
				if ( 'none' == $fsd_options['captions'] ) echo ' selected="selected"';
				echo '>' . __( 'None', 'folderslider') . "</option>\n";
			echo "\t" .	'<option value="filename"';
				if ( 'filename' == $fsd_options['captions'] ) echo ' selected="selected"';
				echo '>' . __('Filename', 'folderslider') . "</option>\n";
			echo "\t" .	'<option value="filenamewithoutextension"';
				if ( 'filenamewithoutextension' == $fsd_options['captions'] ) echo ' selected="selected"';
				echo '>' . __('Filename without extension', 'folderslider') . "</option>\n";	
			echo "\t" .	'<option value="smartfilename"';
				if ( 'smartfilename' == $fsd_options['captions'] ) echo ' selected="selected"';
				echo '>' . __('Smart Filename', 'folderslider') . "</option>\n";	
		echo "</select>\n";
		echo "</td>\n</tr>\n";
		
		
		// CSS
		echo '<tr valign="top">' . "\n";
		echo '<th scope="row"><label for="css">' . __( 'CSS', 'folderslider' ) . '</label></th>' . "\n";
		echo '<td><select name="FolderSlider[css]" id="FolderSlider[css]">' . "\n";		
			echo "\t" .	'<option value="noborder"';
				if ( 'noborder' == $fsd_options['css'] ) echo ' selected="selected"';
				echo '>' . __( 'No border', 'folderslider') . "</option>\n";
			echo "\t" .	'<option value="shadow"';
				if ( 'shadow' == $fsd_options['css'] ) echo ' selected="selected"';
				echo '>' . __('Border with shadow', 'folderslider') . "</option>\n";
			echo "\t" .	'<option value="black-border"';
				if ( 'black-border' == $fsd_options['css'] ) echo ' selected="selected"';
				echo '>' . __('Back border', 'folderslider') . "</option>\n";	
			echo "\t" .	'<option value="white-border"';
				if ( 'white-border' == $fsd_options['css'] ) echo ' selected="selected"';
				echo '>' . __('White border', 'folderslider') . "</option>\n";	
			echo "\t" .	'<option value="gray-border"';
				if ( 'gray-border' == $fsd_options['css'] ) echo ' selected="selected"';
				echo '>' . __('Gray border', 'folderslider') . "</option>\n";	
		echo "</select>\n";
		echo "</td>\n</tr>\n";		

		$this->fsd_option_field( 'width', __( 'Width', 'folderslider' ) , ' px ' . __( '(0 = auto)', 'folderslider' ) );
		$this->fsd_option_field( 'height', __( 'Height', 'folderslider' ), ' px ' . __( '(0 = auto)', 'folderslider' ) );
		
		$this->fsd_option_field( 'speed', __( 'Speed', 'folderslider' ), ' ' . __('seconds', 'folderslider') );

		echo '<tr valign="top">' . "\n";
		echo '<th scope="row">' . __( 'Controls', 'folderslider' ) . "</th>\n";
		echo "<td><fieldset>\n";
		echo '<label for="controls">';
			echo '<input name="FolderSlider[controls]" type="checkbox" id="FolderSlider[controls]" value="1"';
			if ( $fsd_options['controls'] ) echo ' checked="checked"';
			echo '> ' . __('Show Previous/Next Buttons', 'folderslider') . "</label><br />\n";
		echo '<label for="playcontrol">';
			echo '<input name="FolderSlider[playcontrol]" type="checkbox" id="FolderSlider[playcontrol]" value="1"';
			if ( $fsd_options['playcontrol'] ) echo ' checked="checked"';
			echo '> ' . __('Show Play/Pause Button', 'folderslider') . "</label><br />\n";
		echo '<label for="autostart">';
			echo '<input name="FolderSlider[autostart]" type="checkbox" id="FolderSlider[autostart]" value="1"';
			if ( $fsd_options['autostart'] ) echo ' checked="checked"';
			echo '> ' . __('Start Slider Automatically', 'folderslider') . "</label><br />\n";
		echo '<label for="pager">';
			echo '<input name="FolderSlider[pager]" type="checkbox" id="FolderSlider[pager]" value="1"';
			if ( $fsd_options['pager'] ) echo ' checked="checked"';
			echo '> ' . __('Show Pager', 'folderslider') . "</label>\n";
		echo "</fieldset>\n";
		echo "</td>\n</tr>\n";		
		
		echo "</tbody></table>\n";
		submit_button();
		echo "</form></div>\n";

	}
		
} //End Of Class

?>