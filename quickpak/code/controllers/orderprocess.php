<?php
session_start();
class Orderprocess extends Controller {
	
	function index() {
		if(!isset($_SESSION['order'])) {
			redirect('');
		}
		
		$this->load->library('encrypt');
		
		$order = $_SESSION['order'];
		// By default, this sample code is designed to post to our test server for
		// developer accounts: https://test.authorize.net/gateway/transact.dll
		// for real accounts (even in test mode), please make sure that you are
		// posting to: https://secure.authorize.net/gateway/transact.dll
		$post_url = POST_URL;
		
		$subtotal = 0;
		$shipping = 0;
		foreach($order['contents'] as $item) {
			$subtotal += $item['sub'];
			$shipping += $order['shipping'];
			if($item['code'] != 'fak') {
				$shipping += 5.45;
			}
		}
		
		// Referral code discount
		if(isset($order['referral'])) {
			$item = array();
				
			$item['code'] = "ref_code";
			$item['name'] = "Referral Code Discount - 50%";
			$discount = $subtotal * .5;
			$item['cost'] = $discount;
			$item['quantity'] = 1;
			$item['sub'] = $discount;
			
			$_SESSION['order']['contents'][] = $item;
			
			$subtotal = $subtotal - $discount;
		}
		
		/* In occordance with current ecommerce laws, sales tax is only applied to online orders when the order
		originates from a state in which the online store has a physical presence.  In this case, Buzy Bee has
		one location, in Gardena CA.  The tax based on their location is 9.25% */
		$tax = 0;
		if($order['shipState'] == 'CA') {
			$tax = round($subtotal * 0.0925, 2);
		}
		
		$_SESSION['order']['subtotal'] = $subtotal;
		$_SESSION['order']['tax'] = $tax;
		$_SESSION['order']['ship_handling'] = $shipping;
		
		$total = round($subtotal + $tax + $shipping, 2);
		$_SESSION['order']['total'] = $total;
		
		
		$ccNumber = $this->encrypt->decode($order['ccNumber']);
		$ccExpiration = $this->encrypt->decode($order['ccExpiration']);
		
		$post_values = array(
			
			// the API Login ID and Transaction Key must be replaced with valid values
			"x_login"				=> X_LOGIN,
			"x_tran_key"			=> X_TRAN_KEY,
		
			"x_version"				=> "3.1",
			"x_delim_data"			=> "TRUE",
			"x_delim_char"			=> "|",
			"x_relay_response"		=> "FALSE",
			"x_email_customer"		=> "FALSE",
		
			"x_type"				=> "AUTH_CAPTURE",
			"x_method"				=> "CC",			
			
			"x_card_num"			=> $ccNumber,
			"x_exp_date"			=> $ccExpiration,
		
			"x_amount"				=> $total,
			"x_description"			=> "Quick Pak Bag Transaction",
		
			"x_first_name"			=> $order['billFirst'],
			"x_last_name"			=> $order['billLast'],
			"x_address"				=> $order['billAddress1'],
			"x_city"				=> $order['billCity'],
			"x_state"				=> $order['billState'],
			"x_zip"					=> $order['billZip'],
			"x_country"				=> $order['billCountry'],
			"x_phone"				=> $order['userPhone'],
			"x_email"				=> $order['userEmail'],
			
			"x_ship_to_first_name"	=> $order['shipFirst'],
			"x_ship_to_last_name"	=> $order['shipLast'],
			"x_ship_to_address"		=> $order['shipAddress1'],
			"x_ship_to_city"		=> $order['shipCity'],
			"x_ship_to_state"		=> $order['shipState'],
			"x_ship_to_zip"			=> $order['shipZip'],
			"x_ship_to_country"		=> $order['shipCountry']
			// Additional fields can be added here as outlined in the AIM integration
			// guide at: http://developer.authorize.net
		);
		
		// This section takes the input fields and converts them to the proper format
		// for an http post.  For example: "x_login=username&x_tran_key=a1B2c3D4"
		$post_string = "";
		foreach($post_values as $key => $value) {
			$post_string .= "$key=" . urlencode($value) . "&";
		}
		$post_string = rtrim($post_string, "& ");
		
		// This sample code uses the CURL library for php to establish a connection,
		// submit the post, and record the response.
		// If you receive an error, you may want to ensure that you have the curl
		// library enabled in your php configuration
		$request = curl_init($post_url); // initiate curl object
			curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
			curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
			curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data
			curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
			$post_response = curl_exec($request); // execute curl post and store results in $post_response
			// additional options may be required depending upon your server configuration
			// you can find documentation on curl options at http://www.php.net/curl_setopt
		curl_close ($request); // close curl object
		
		// This line takes the response and breaks it into an array using the specified delimiting character
		$response_array = explode($post_values["x_delim_char"], $post_response);
		
		switch($response_array[0]) {
			case 1: // approved
				$_SESSION['order']['transaction'] = $response_array[6];
				
				// Delete referral code from DB to prevent further use
				if(isset($order['referral'])) {
					$this->db->query("DELETE FROM referral WHERE code='".$order['referral']."' AND referrer='".$order['userEmail']."'");
				}
			
				redirect('/thankyou/');
				break;
			case 2: // declined
				$data['error'] = $response_array[3];
				break;
			case 3: // error
				$data['error'] = $response_array[3];
				break;
			case 4: // held for review
				$data['error'] = $response_array[3];
				break;
		}
		
		$data['page'] = 'orderprocess_view';
		$this->load->view('main/main_view', $data);
	}
	
}

//end orderprocess