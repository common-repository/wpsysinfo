<?php
/*
Plugin Name: WPSysInfo
Description: Shows Systeminfo of your Wordpress Hosting environment
Version: 1.0.0
Author: Dravion
Author URI: https://www.dravionsoftware.com/wpp/wpsysinfo
License: GPL2
*/


add_action( 'plugins_loaded', 'wpsysinfo_acl_permissions' );

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Direct access restricted.';
	exit;
}

/* Check currently logged in user */
function wpsysinfo_acl_permissions()
{
    /* load Admin page */
    $user = wp_get_current_user();
    if ( in_array( 'administrator', (array) $user->roles ) ) {
        wpsysinfo_load_admin_menue();    
    }    
}

/* Check if HTTP-Server supports GZIP compression */
function wpsysinfo_check_gzip_compression() {
	
	if (count(array_intersect(['mod_deflate', 'mod_gzip'],  apache_get_modules())) > 0) {
		return true;
	} else {
		false;
	}
}

/* Load Wordpress backend menue - Admin  */
function wpsysinfo_load_admin_menue() {

    /* Create Admin page */
    add_action("admin_menu", "wpsysinfo_plugin_setup_menu");

    function wpsysinfo_plugin_setup_menu() {
        add_menu_page( "wpsysinfo", "WPSysInfo", "manage_options", "wpsysinfo-plugin", "wpsysinfo_user_init" );
    }  

    function wpsysinfo_user_init() {		
		
        echo ("<br><h3>WPSysInfo</h3>");
		echo "<h4>";
		echo "PHP version: " .esc_html(phpversion())."</br>";					
		echo "PHP Memory limit: ".esc_html(ini_get('memory_limit')) ."</br>"; 
		echo "Server OS type: ". esc_html(PHP_OS) ."</br>"; 				
		echo "HTTP version: ".esc_html($_SERVER["SERVER_PROTOCOL"]) ."</br>";
		echo "HTTP ip address: " .esc_html($_SERVER['SERVER_ADDR']) ."</br>";
		echo "HTTPS (SSL/TLS): " .esc_html($_SERVER['HTTPS']) ."</br>";
		echo "HTTP-Software: " .esc_html($_SERVER['SERVER_SOFTWARE']) ."</br>";
		echo "Admin email: " .esc_html($_SERVER['SERVER_ADMIN']) ."</br>";
		echo "Host: " .esc_html($_SERVER['HTTP_HOST']) ."</br>";		
		echo "Your IP address is: " .esc_html(getenv("REMOTE_ADDR")) ."</br>";
		
		if (wpsysinfo_check_gzip_compression()) {
			echo esc_html("GZIP-Compression: Enabled");
		} else {
			echo esc_html("GZIP-Compression: Disabled");
		}
		
		echo "</p>";
		
		/* List PHP Extensions */
		echo "<h2>PHP Extensions</h2>";		
		echo "<select name='Cars' size='10";
		
		foreach (get_loaded_extensions() as $i => $ext) {					
				$ext .' => '. phpversion($ext);
				echo "<option value='x'> ". esc_html($ext)." </option>";  				
			}		
		}
		echo "</select";
		
	echo "</h4>";		

}