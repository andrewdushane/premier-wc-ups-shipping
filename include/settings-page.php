<div class="wrap">
	
	<div id="icon-options-general" class="icon32"></div>
	<h2>UPS Shipping for WooCommerce Settings</h2>
	
	<div id="poststuff">
	
		<div id="post-body" class="metabox-holder columns-2">
		
			<!-- main content -->
			<div id="post-body-content">
				
				<div class="meta-box-sortables ui-sortable">
					
					<div class="postbox">
					
						<h3><span>UPS Shipper Information</span></h3>
						<div class="inside">
							
							<?php 
							/*** Display UPS shipper information pass/fail message if set ***/
							if(isset($test_message)) {
								echo '<p><strong>' . $test_message . '</strong></p>';
							}
							/*** Show form to enter UPS shipper information  if it's not set, if validation failed or the Update button has been pressed,  ***/
							if( ( !isset($ups_Verified) || !$ups_Verified ) || ( isset($upstest) && !$upstest ) || $ups_shipping_options_update == 'y' ) : ?>
							<p>Register for an account on the <a href="https://www.ups.com/upsdeveloperkit" target="_blank">UPS Developer site</a> to obtain API credentials.</p>
							<form name="ups_shipping_options_form" method="post" action="">
								<input type="hidden" name="ups_shipping_options_submitted" value="y">
								<table class="form-table">
									<tr>
										<td><label for="ups_AccessLicenseNumber">Access License Number:</label></td>
										<td><input name="ups_AccessLicenseNumber" id="ups_AccessLicenseNumber" type="text" value="" class="regular-text" /></td>
									</tr>
									<tr>
										<td><label for="ups_UserID">User ID:</label></td>
										<td><input name="ups_UserID" id="ups_UserID" type="text" value="" class="regular-text" /></td>
									</tr>
									<tr>
										<td><label for="ups_Password">Password:</label></td>
										<td><input name="ups_Password" id="ups_Password" type="text" value="" class="regular-text" /></td>
									</tr>
									<tr>
										<td><label for="ups_FromPostalCode">Shipper Postal Code (From Zip):</label></td>
										<td><input name="ups_FromPostalCode" id="ups_FromPostalCode" type="text" value="" class="regular-text" /></td>
									</tr>
									<tr>
										<td><label for="ups_ShipperNumber">Shipper Number (UPS Account Number):</label></td>
										<td><input name="ups_ShipperNumber" id="ups_ShipperNumber" type="text" value="" class="regular-text" /></td>
									</tr>
								</table>
								<p><input class="button-primary" type="submit" name="Submit" value="Submit" /></p>
							</form>
							<?php endif ?>
								
							<?php 
							/*** Display shipper information if it's set ***/ 
							if( ( isset($ups_Verified) && $ups_Verified ) || $ups_shipping_options_update == 'y' ) : ?>
								
								<?php 
								/*** Display 'current settings' if validation failed or update button has been pressed ***/
								if( !$upstest || $ups_shipping_options_update == 'y' ) : ?>
									<p>Current settings:</p>
								<?php endif ?>
								<table class="widefat">
									<tr>
										<td>Access License Number:</td>
										<td><?php echo $ups_AccessLicenseNumber; ?></td>
									</tr>
									<tr>
										<td>User ID:</td>
										<td><?php echo $ups_UserID; ?></td>
									</tr>
									<tr>
										<td>Password:</td>
										<td><?php echo $ups_Password; ?></td>
									</tr>
									<tr>
										<td>Shipper Postal Code:</td>
										<td><?php echo $ups_FromPostalCode; ?></td>
									</tr>
									<tr>
										<td>Shipper Number (UPS Account Number):</td>
										<td><?php echo $ups_ShipperNumber; ?></td>
									</tr>
									<tr>
										<td>Last updated:</td>
										<td><?php echo date_i18n('M jS y', $ups_LastUpdated); ?></td>
									</tr>
								</table>
								<?php 
								/*** Show Update button if it hasn't been pressed ***/
								if( $ups_shipping_options_update != 'y' ) : ?>
									<form name="ups_shipping_options_update" method="post" action="">
										<input type="hidden" name="ups_shipping_options_update" value="y">
										<p><input class="button-primary" type="submit" name="Update" value="Change/Update" /></p>
									</form>
								<?php endif ?>
							<?php endif ?>
							
						</div> <!-- .inside -->
					</div> <!-- .postbox -->
					
				</div> <!-- .meta-box-sortables .ui-sortable -->
				
			</div> <!-- post-body-content -->
			
			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">
				
				<div class="meta-box-sortables">
					
					<div class="postbox">
						
						<div class="inside">
							<h2>System Information</h2>
							<p>This plugin sends shipping requests to UPS using the cURL method. Verify that cURL is enabled on your server in the <a href="<?php echo admin_url( 'admin.php?page=wc-status' ); ?>" target="_blank">WooCommerce System Status page</a> under fsockopen/cURL.</p>
							<h2>Developer Information</h2>
							<p>Author: Andrew Dushane, <a href="http://premierprograming.com" target="_blank">Premier Programing</a></p>
						</div><!-- .inside -->
						
					</div>  <!-- .postbox -->
					
				</div> <!-- .meta-box-sortables -->
				
			</div> <!-- #postbox-container-1 .postbox-container -->
			
		</div> <!-- #post-body .metabox-holder .columns-2 -->
		
		<br class="clear">
	</div> <!-- #poststuff -->
	
</div> <!-- .wrap -->
