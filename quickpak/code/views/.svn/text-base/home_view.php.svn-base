<?php
    $states_arr = array('AL'=>"Alabama",'AK'=>"Alaska",'AZ'=>"Arizona",'AR'=>"Arkansas",'CA'=>"California",'CO'=>"Colorado",'CT'=>"Connecticut",'DE'=>"Delaware",'DC'=>"District Of Columbia",'FL'=>"Florida",'GA'=>"Georgia",'HI'=>"Hawaii",'ID'=>"Idaho",'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa",  'KS'=>"Kansas",'KY'=>"Kentucky",'LA'=>"Louisiana",'ME'=>"Maine",'MD'=>"Maryland", 'MA'=>"Massachusetts",'MI'=>"Michigan",'MN'=>"Minnesota",'MS'=>"Mississippi",'MO'=>"Missouri",'MT'=>"Montana",'NE'=>"Nebraska",'NV'=>"Nevada",'NH'=>"New Hampshire",'NJ'=>"New Jersey",'NM'=>"New Mexico",'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma", 'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming", 'N/A'=>"Outside US");
?>

<script language="javascript">
	jQuery(document).ready(function() {
		jQuery("#bill_same").change(function() {
			if(jQuery("#bill_same:checked").val() != null) {
				jQuery("#ship_first").val(jQuery("#first").val())
				jQuery("#ship_last").val(jQuery("#last").val())
				jQuery("#ship_address1").val(jQuery("#address1").val())
				jQuery("#ship_address2").val(jQuery("#address2").val())
				jQuery("#ship_city").val(jQuery("#city").val())
				jQuery("#ship_state").val(jQuery("#state").val())
				jQuery("#ship_zip").val(jQuery("#zip").val())
				jQuery("#ship_country").val(jQuery("#country").val())
			}
		})
		
		jQuery("#country").change(function() {
			if(jQuery("#country").val() != 'UnitedStates') {
				jQuery("#state").val('N/A');
			}
		})
		
		jQuery("#ship_country").change(function() {
			if(jQuery("#ship_country").val() != 'UnitedStates') {
				jQuery("#ship_state").val('N/A');
			}
		})
				
	});
</script>



<DIV class="CONTENT_TOP"><img src="/assets/image/home_content_top.jpg" width="942" height="9" /></DIV>

<DIV class="CONTENT_MIDDLE">
	
    <DIV class="CONTENT_MIDDLE_LEFT"><img src="/assets/image/title_our_products.jpg" width="201" height="44" /><br /><br /><div id="fadeshow1"></div>
			<br /><br />
			<DIV class="right"><img src="/assets/image/color_options.jpg" /></DIV><!-- /.right -->
    
    	<br /><br />
    	<h1>Quick Pak Bag</h1>
    	<br /><br /><p>The Quick Pak is the ultimate multi-use organizer that will streamline your life. The Quick Pak’s clear compartments keep you organized!<br /><br />It is easy to open and very functional. When you’re not using the Quick Pak Bag it is fully collapsible for your convenience.<br /><br />The Quick Pak can be used for a Baby Bag, Lunch Bag, Cosmetic Bag, Travel Bag, or Emergency Readiness Bag, you can carry toys, beads, dolls, fishing equipment, etc.</p>
        <br /><br />
        <p align="center"><img src="/assets/image/three_bags.jpg" /></p>
    
    </DIV><!-- /.CONTENT_MIDDLE_LEFT -->
            
		<DIV class="CONTENT_MIDDLE_RIGHT"><img src="/assets/image/title_order_now.jpg" />
			
			<DIV class="small_red"><?php echo validation_errors(); ?></DIV>
			
			<br />
				<form method="post" action="/home/process">
					<h3>Bag Quantity</h3>
					<table id="bag_quantity">
						<tr>
							<td width="70">
								Black:*<br />
								<input name="black" type="text" value="<?php if(set_value('black') == '') { echo "0"; } else { echo set_value('black'); } ?>" size="3" />
							</td>
							<td>
								Blue:*<br />
								<input name="blue" type="text" value="<?php if(set_value('blue') == '') { echo "0"; } else { echo set_value('blue'); } ?>" size="3" />
							</td>
                            <td>
                            	Taupe:*<br />
								<input name="taupe" type="text" value="<?php if(set_value('taupe') == '') { echo "0"; } else { echo set_value('taupe'); } ?>" size="3" />
                            </td>
						</tr>
						<tr>
							<td>
								Pink:*<br />
								<input name="pink" type="text" value="<?php if(set_value('pink') == '') { echo "0"; } else { echo set_value('pink'); } ?>" size="3" />
							</td>
							<td>
								Comouflage:*<br />
								<input name="camo" type="text" value="<?php if(set_value('camo') == '') { echo "0"; } else { echo set_value('camo'); } ?>" size="3" />
							</td>
                            <td>&nbsp;</td>
						</tr>
					</table>
					<input name="bags" type="hidden" />
					Shipping Options:*<br />
					<select name="shipping">
						<option value="ground">Ground Shipping</option>
						<option value="priority">Priority Shipping</option>
					</select><br />
					Referral Code:<br />
					<input name="referral" type="text" value="<?php echo set_value('referral'); ?>" size="9" maxlength="8" /><br />
		
					<h3>Billing Information</h3>
					First Name:*<br />
					<input name="first" id="first" type="text" value="<?php echo set_value('first'); ?>" /><br />
					Last Name:*<br />
					<input name="last" id="last" type="text" value="<?php echo set_value('last'); ?>" /><br />
					Address 1:*<br />
					<input name="address1" id="address1" type="text" value="<?php echo set_value('address1'); ?>" /><br />
					Address2:<br />
					<input name="address2" id="address2" type="text" value="<?php echo set_value('address2'); ?>" /><br />
					City:*<br />
					<input name="city" id="city" type="text" value="<?php echo set_value('city'); ?>" /><br />
					State:*<br />
					
					<select name="state" id="state">
						<?php
						foreach($states_arr as $k => $v) {
							$s = (set_value('state') == $k) ? ' SELECTED' : '';
										echo '<option value="'.$k.'"'.$s.'>'.$v.'</option>'."\n";     
								} ?>
					</select><br />
		
					Zip:*<br />
					<input name="zip" id="zip" type="text" size="6" maxlength="5" value="<?php echo set_value('zip'); ?>" /><br />
					Country:*<br />
					
					<select name="country" id="country">
						<option value="UnitedStates" <?php if(set_value('country') == 'UnitedStates')  echo ' SELECTED'; ?>>United States</option>
						<option value="Canada" <?php if(set_value('country') == 'Canada') echo ' SELECTED'; ?>>Canada</option>
						<option value="PuertoRico" <?php if(set_value('country') == 'PuertoRico') echo ' SELECTED'; ?>>Puerto Rico</option>
					</select><br />
	
	
					Email Address:*<br />
					<input name="email" type="text" value="<?php echo set_value('email'); ?>" /><br />
					Phone:*<br />
					<input name="phone1" type="text" size="3" maxlength="3" value="<?php echo set_value('phone1'); ?>" />
					-
					<input name="phone2" type="text" size="3" maxlength="3" value="<?php echo set_value('phone2'); ?>" />
					-
					<input name="phone3" type="text" size="4" maxlength="4" value="<?php echo set_value('phone3'); ?>" /><br />
		
					<h3>Credit Card Information</h3>
					Name on Credit Card:*<br />
					<input name="ccName" type="text" /><br />
					Credit Card Type:*<br />
					<select name="ccType">
						<option value="Discover">Discover</option>
						<option value="MasterCard">MasterCard</option>
						<option value="Visa">Visa</option>
					</select><br />
	
					Credit Card Number:*<br />
					<input name="ccNumber" type="text" size="17" maxlength="16" /><br/>
					Expiration:*<br />
					<input name="ccMonth" type="text" size="3" maxlength="2" />
					/
					<input name="ccYear" type="text" size="5" maxlength="4" /><br />
					
					<h3>Shipping Information</h3>
					<input name="bill_same" id="bill_same" type="checkbox" />Same as billing information.<br />
					First Name:*<br />
					<input name="ship_first" id="ship_first" type="text" value="<?php echo set_value('ship_first'); ?>" /><br />
					Last Name:*<br />
					<input name="ship_last" id="ship_last" type="text" value="<?php echo set_value('ship_last'); ?>" /><br />
					Address 1:*<br />
					<input name="ship_address1" id="ship_address1" type="text" value="<?php echo set_value('ship_address1'); ?>" /><br />
					Address2:<br />
					<input name="ship_address2" id="ship_address2" type="text" value="<?php echo set_value('ship_address2'); ?>" /><br />
					City:*<br />
					<input name="ship_city" id="ship_city" type="text" value="<?php echo set_value('ship_city'); ?>" /><br />
					State:*<br />
					
					<select name="ship_state" id="ship_state">
						<?php
						foreach($states_arr as $k => $v) {
							$s = (set_value('ship_state') == $k) ? ' SELECTED' : '';
										echo '<option value="'.$k.'"'.$s.'>'.$v.'</option>'."\n";     
								} ?>
					</select><br />
					Zip:*<br />
					<input name="ship_zip" id="ship_zip" type="text" size="6" maxlength="5" value="<?php echo set_value('ship_zip'); ?>" /><br />
					Country:*<br />
					<select name="ship_country" id="ship_country">
						<option value="UnitedStates" <?php if(set_value('ship_country') == 'UnitedStates') echo ' SELECTED'; ?>>United States</option>
						<option value="Canada" <?php if(set_value('ship_country') == 'Canada') echo ' SELECTED'; ?>>Canada</option>
						<option value="PuertoRico" <?php if(set_value('ship_country') == 'PuertoRico') echo ' SELECTED'; ?>>Puerto Rico</option>
					</select><br /><br />(*) required<br /><br />
					<input type="image" src="/assets/image/but_continue.jpg" align="left" />
				</form>
				
			</DIV><!-- /.CONTENT_MIDDLE_RIGHT -->
		<DIV style="clear:both"></DIV>
	</DIV><!-- /.CONTENT_MIDDLE -->

<DIV class="CONTENT_BOTTOM"><img src="/assets/image/home_content_bottom.jpg" width="942" height="9" /></DIV><DIV style="clear:both"></DIV>