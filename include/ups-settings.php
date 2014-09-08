<?php

$ups_options = array();

/*** Add options page to admin menu ***/
function ups_shipping_menu() {
	add_submenu_page(
		'woocommerce',
		'UPS Shipping for WooCommerce Settings',
		'UPS Shipping',
		'manage_options',
		'ups-shipping',
		'ups_shipping_options_page'
	);
}

add_action('admin_menu', 'ups_shipping_menu');

/*** Create options page ***/
function ups_shipping_options_page() {
	
	global $ups_options;
	global $upstest;
	
	/*** Exit if current user is not admin ***/
	if( !current_user_can('manage_options')) {
		wp_die( 'You do not have sufficient permissions to access this page.' );
	}
	
	/*** If UPS info has been entered, validate with UPS ***/
	$ups_options = null;
	update_option('wc_ups_shipping', $ups_options);
	if( isset($_POST['ups_shipping_options_submitted']) ) {
		$submit_check = esc_html($_POST['ups_shipping_options_submitted']);
		if ( $submit_check = 'y' ) {
			$ups_options['ups_AccessLicenseNumber']  = esc_html($_POST['ups_AccessLicenseNumber']);
			$ups_options['ups_UserID']				 = esc_html($_POST['ups_UserID']);
			$ups_options['ups_Password'] 		 	 = esc_html($_POST['ups_Password']);
			$ups_options['ups_FromPostalCode']		 = esc_html($_POST['ups_FromPostalCode']);
			$ups_options['ups_ShipperNumber']		 = esc_html($_POST['ups_ShipperNumber']);
			$ups_options['ups_LastUpdated']			 = time();
			include_once( plugin_dir_path( __FILE__ ) . 'ups-rate.php' );
			$upstest = intval(ups(10001,'US','03',1,6,6,6,$ups_options['ups_AccessLicenseNumber'],$ups_options['ups_UserID'],$ups_options['ups_Password'],$ups_options['ups_FromPostalCode'],$ups_options['ups_ShipperNumber']));
			/*** Pass/fail message for UPS shipper validation ***/
			if(!( $upstest > 0 )) {
				$upstest = false;
				$test_message = "There was a problem validating your UPS shipper information. Please verify and re-enter.";
			}
			else {
				$ups_options['ups_Verified'] = true;
				$test_message = "UPS shipper information successfully verified. You are ready to enable UPS shipping methods in WooCommerce Shipping settings.";
				update_option('wc_ups_shipping', $ups_options);
			}
			
		}
	}
	
	
	/*** Check if Update button has been pressed ***/
	if( isset($_POST['ups_shipping_options_update']) ) {
		$ups_shipping_options_update = esc_html($_POST['ups_shipping_options_update']);
	}
	
	else $ups_shipping_options_update = 'n';
	
	/*** Get UPS information from options table ***/
	$ups_options = get_option('wc_ups_shipping');
	
	if ($ups_options != '') {
		$ups_Verified			 = $ups_options['ups_Verified'];
		$ups_AccessLicenseNumber = $ups_options['ups_AccessLicenseNumber'];
		$ups_UserID 			 = $ups_options['ups_UserID'];
		$ups_Password			 = $ups_options['ups_Password'];
		$ups_FromPostalCode		 = $ups_options['ups_FromPostalCode'];
		$ups_ShipperNumber 		 = $ups_options['ups_ShipperNumber'];
		$ups_LastUpdated		 = $ups_options['ups_LastUpdated'];
	}
	
	include('settings-page.php');
	
}
