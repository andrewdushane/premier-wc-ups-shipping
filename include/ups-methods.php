<?php
/**
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	/*** Check that UPS shipper information has been verified ***/
	$ups_options = get_option('wc_ups_shipping');
	if( is_array($ups_options) && ( array_key_exists('ups_Verified', $ups_options) ) ) {
		$ups_Verified = $ups_options['ups_Verified'];
	}
	if( isset($ups_Verified) && $ups_Verified ) {
 
		/*** Parent class for UPS methods ***/
		//Initialize WooCommerce shipping settings for each method
		//Create rate calculation function
		function ups_init() {
			if ( ! class_exists( 'WC_UPS' ) ) {
				class WC_UPS extends WC_Shipping_Method {
					/**
					 * General constructor for UPS methods
					 *
					 * @access public
					 * @return void
					 */

					public function __construct() {
						$this->init();
					}
	 
					/**
					 * Init method settings
					 *
					 * @access public
					 * @return void
					 */
					function init() {
						// Load the settings API

						$label = 'Enable ' . $this->method_title;
						$default = $this->method_title;
					
						$this->form_fields = array(
							'enabled' => array(
														'title' 		=> __( 'Enable/Disable', 'woocommerce' ),
														'type' 			=> 'checkbox',
														'label' 		=> __( $label, 'woocommerce' ),
														'default' 		=> 'no',
												),
							'title' => array(
														'title' 		=> __( 'Method Title', 'woocommerce' ),
														'type' 			=> 'text',
														'description' 	=> __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
														'default'		=> __( $default, 'woocommerce' ),
												),
						);  
							
						$this->init_settings();
							$this->enabled		  = $this->settings['enabled'];
							$this->title 		  = $this->settings['title'];
					
						// Save settings in admin
						add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );

	
					}

					/**
					 * calculate_shipping function
					 * pass cart information and service code to ups-rate.php
					 *
					 * @access public
					 * @param mixed $package
					 * @return void
					 */
				
				
					public function calculate_shipping( $package ) { 
						$ups_options = get_option('wc_ups_shipping');
							if ($ups_options && ($ups_options != '') ) {
								$AccessLicenseNumber = $ups_options['ups_AccessLicenseNumber'];
								$UserID 			 = $ups_options['ups_UserID'];
								$Password			 = $ups_options['ups_Password'];
								$FromPostalCode		 = $ups_options['ups_FromPostalCode'];
								$ShipperNumber 		 = $ups_options['ups_ShipperNumber'];
							}
						include_once( plugin_dir_path( __FILE__ ) . 'ups-rate.php' );
						$service = $this->service;
						global $woocommerce;
						$length = 0;
						$width = 0;
						$height = 0;
						foreach ( $woocommerce->cart->get_cart() as $item_id => $values ) {
						        $_product = $values['data'];
								$length = $length + $_product->length;
								$width = $width + $_product->width;
								$height = $height + $_product->height;
		       			}
						$weight = $woocommerce->cart->cart_contents_weight;
						$dest_zip =  $woocommerce->customer->get_postcode(); 
						$dest_country =  $woocommerce->customer->get_country(); 
						if ( isset($length, $width, $height, $weight, $dest_zip, $dest_country) && ($dest_zip != '') ) {
							$upsrate = (ups($dest_zip,$dest_country,$service,$weight,$length,$width,$height,$AccessLicenseNumber,$UserID,$Password,$FromPostalCode,$ShipperNumber));
							if($upsrate > 0) {
								$rate = array(
								'id' 	   => $this->id,
								'label'	   => $this->title,
								'cost'	   => $upsrate,
								'calc_tax' => 'per_item'
								);
								// Register the rate
								$this->add_rate( $rate );
							} else return;
						} else return;
					}
				}
			}
		}

		add_action( 'woocommerce_shipping_init', 'ups_init' );


		/*** UPS Ground class ***/
		function ups_ground_init() {
			if ( ! class_exists( 'WC_UPS_Ground' ) ) {
				class WC_UPS_Ground extends WC_UPS {
					/**
					 * Constructor for Ground
					 *
					 * @access public
					 * @return void
					 */
					public function __construct() {
						$this->id                 = ('ups_ground'); // Method ID
						$this->method_title       = __( 'UPS Ground' );  // Title shown in admin
						$this->method_description = __( 'Ship UPS Ground' ); // Description shown in admin
						$this->service            = '03'; //UPS service code
						$this->init();
					}
				}
			}
		}
		 
		add_action( 'woocommerce_shipping_init', 'ups_ground_init' );
	 
		function add_ups_ground( $methods ) {
			$methods[] = 'WC_UPS_Ground';
			return $methods;
		}
	 
		add_filter( 'woocommerce_shipping_methods', 'add_ups_ground' );
	
	
		/*** UPS Second Day Air class ***/
		function ups_second_day_init() {
			if ( ! class_exists( 'WC_UPS_Second_Day' ) ) {
				class WC_UPS_Second_Day extends WC_UPS {
					/**
					 * Constructor for Ground
					 *
					 * @access public
					 * @return void
					 */
					public function __construct() {
						$this->id                 = ('ups_second_day'); // Method ID
						$this->method_title       = __( 'UPS Second Day Air' );  // Title shown in admin
						$this->method_description = __( 'Ship UPS Second Day Air' ); // Description shown in admin
						$this->service            = '02'; //UPS service code
						$this->init();
					}
				}
			}
		}
		 
		add_action( 'woocommerce_shipping_init', 'ups_second_day_init' );
	 
		function add_ups_second_day( $methods ) {
			$methods[] = 'WC_UPS_Second_Day';
			return $methods;
		}
	 
		add_filter( 'woocommerce_shipping_methods', 'add_ups_second_day' );
	
	
		/*** UPS Next Day Air class ***/
		function ups_next_day_init() {
			if ( ! class_exists( 'WC_UPS_Next_Day' ) ) {
				class WC_UPS_Next_Day extends WC_UPS {
					/**
					 * Constructor for Ground
					 *
					 * @access public
					 * @return void
					 */
					public function __construct() {
						$this->id                 = ('ups_next_day'); // Method ID
						$this->method_title       = __( 'UPS Next Day Air' );  // Title shown in admin
						$this->method_description = __( 'Ship UPS Next Day Air' ); // Description shown in admin
						$this->service            = '01'; //UPS service code
						$this->init();
					}
				}
			}
		}
		 
		add_action( 'woocommerce_shipping_init', 'ups_next_day_init' );
	 
		function add_ups_next_day( $methods ) {
			$methods[] = 'WC_UPS_Next_Day';
			return $methods;
		}
	 
		add_filter( 'woocommerce_shipping_methods', 'add_ups_next_day' );
		
		/*** UPS Worldwide Express class ***/
		function ups_ww_express_init() {
			if ( ! class_exists( 'WC_UPS_WW_Express' ) ) {
				class WC_UPS_WW_Express extends WC_UPS {
					/**
					 * Constructor for Ground
					 *
					 * @access public
					 * @return void
					 */
					public function __construct() {
						$this->id                 = ('ups_ww_express'); // Method ID
						$this->method_title       = __( 'UPS Worldwide Express' );  // Title shown in admin
						$this->method_description = __( 'Ship UPS Worldwide Express' ); // Description shown in admin
						$this->service            = '07'; //UPS service code
						$this->init();
					}
				}
			}
		}
		 
		add_action( 'woocommerce_shipping_init', 'ups_ww_express_init' );
	 
		function add_ups_ww_express( $methods ) {
			$methods[] = 'WC_UPS_WW_Express';
			return $methods;
		}
	 
		add_filter( 'woocommerce_shipping_methods', 'add_ups_ww_express' );
	}
} 
