<?php
session_start();
class Home extends Controller {
	
	function index() {
		$data['page'] = "home_view";
		
		$this->load->view('main/home_view', $data);
	}
	
	function process() {
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('black', 'Black Bag Quantity', 'required|is_natural');
		$this->form_validation->set_rules('blue', 'Blue Bag Quantity', 'required|is_natural');
		$this->form_validation->set_rules('pink', 'Pink Bag Quantity', 'required|is_natural');
		$this->form_validation->set_rules('camo', 'Camouflage Bag Quantity', 'required|is_natural');
		$this->form_validation->set_rules('taupe', 'Taupe Bag Quantity', 'required|is_natural');
		$this->form_validation->set_rules('bags', '', 'callback_bag_check');
		
		$this->form_validation->set_rules('referral', 'Referral Code', 'callback_referral_check');
		
		$this->form_validation->set_rules('first', 'Billing First Name', 'trim|required');
		$this->form_validation->set_rules('last', 'Billing Last Name', 'trim|required');
		$this->form_validation->set_rules('address1', 'Billing Address 1', 'trim|equired');
		$this->form_validation->set_rules('city', 'Billing City', 'trim|required');
		$this->form_validation->set_rules('zip', 'Billing Zip Code', 'required|min_length[5]|is_natural_no_zero');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('phone1', 'Phone 1', 'required|min_length[3]|is_natural_no_zero');
		$this->form_validation->set_rules('phone2', 'Phone 2', 'required|min_length[3]|is_natural_no_zero');
		$this->form_validation->set_rules('phone3', 'Phone 3', 'required|min_length[4]|is_natural_no_zero');
		
		$this->form_validation->set_rules('ship_first', 'Shipping First Name', 'trim|required');
		$this->form_validation->set_rules('ship_last', 'Shipping Last Name', 'trim|required');
		$this->form_validation->set_rules('ship_address1', 'Shipping Address 1', 'trim|required');
		$this->form_validation->set_rules('ship_city', 'Shipping City', 'trim|required');
		$this->form_validation->set_rules('ship_zip', 'Shipping Zip Code', 'required|min_length[5]|is_natural_no_zero');
		
		$this->form_validation->set_rules('ccName', 'Name on Credit Card', 'trim|required');
		$this->form_validation->set_rules('ccNumber', 'Credit Card Number', 'required|min_length[16]|is_natural_no_zero');
		$this->form_validation->set_rules('ccMonth', 'Month Expiration', 'required|min_length[2]|is_natural_no_zero');
		$this->form_validation->set_rules('ccYear', 'Year Expiration', 'required|min_length[4]|is_natural_no_zero');
		
		if($this->form_validation->run() == FALSE) {
			$data['page'] = "home_view";
			
			$this->load->view('main/home_view', $data);
		} else {
			foreach($_POST as $key => $value) {
				$_POST[$key] = mysql_real_escape_string($value);
			}
		
			$insert = array(
				'user_first' => $_POST['first'],
				'user_last' => $_POST['last'],
				'user_address1' => $_POST['address1'],
				'user_address2' => $_POST['address2'],
				'user_city' => $_POST['city'],
				'user_state' => $_POST['state'],
				'user_zip' => $_POST['zip'],
				'user_country' => $_POST['country'],
				'user_email' => $_POST['email'],
				'user_phone' => $_POST['phone1'].'-'.$_POST['phone2'].'-'.$_POST['phone3']
			);
			
			$this->db->insert('user', $insert);
			
			$this->load->library('encrypt');
			
			$order = array();
			$order['userID'] = $this->db->insert_id();
			$order['billFirst'] = $_POST['first'];
			$order['billLast'] = $_POST['last'];
			$order['billAddress1'] = $_POST['address1'];
			$order['billAddress2'] = $_POST['address2'];
			$order['billCity'] = $_POST['city'];
			$order['billState'] = $_POST['state'];
			$order['billZip'] = $_POST['zip'];
			$order['billCountry'] = $_POST['country'];
			$order['userEmail'] = $_POST['email'];
			$order['userPhone'] = $_POST['phone1'].'-'.$_POST['phone2'].'-'.$_POST['phone3'];
			
			$order['ccName'] = $_POST['ccName'];
			$order['ccType'] = $_POST['ccType'];
			$order['ccNumber'] = $this->encrypt->encode($_POST['ccNumber']);
			$order['ccExpiration'] = $this->encrypt->encode($_POST['ccMonth'].$_POST['ccYear']);
			
			$order['shipFirst'] = $_POST['ship_first'];
			$order['shipLast'] = $_POST['ship_last'];
			$order['shipAddress1'] = $_POST['ship_address1'];
			$order['shipAddress2'] = $_POST['ship_address2'];
			$order['shipCity'] = $_POST['ship_city'];
			$order['shipState'] = $_POST['ship_state'];
			$order['shipZip'] = $_POST['ship_zip'];
			$order['shipCountry'] = $_POST['ship_country'];
			
			if($_POST['ship_country'] == 'Canada' || $_POST['ship_country'] == 'PuertoRico') {
				if($_POST['shipping'] == 'ground') {
					$shipping = 25.00;
				} else {
					$shipping = 58.00;
				}
			} elseif($_POST['ship_country'] == 'UnitedStates' && ($_POST['ship_state'] == 'AK' || $_POST['ship_state'] == 'HI')) {
				if($_POST['shipping'] == 'ground') {
					$shipping = 25.00;
				} else {
					$shipping = 35.00;
				}
			} else {
				if($_POST['shipping'] == 'ground') {
					$shipping = 3.50;
				} else {
					$shipping = 15.00;
				}
			}
			
			$order['shipping'] = $shipping;
			$order['shipType'] = $_POST['shipping'];
			
			if(!empty($_POST['referral'])) {
				$order['referral'] = $_POST['referral'];
			}
			
			$order['contents'] = array();
			
			if($_POST['black'] > 0) {
				$item = array();
				
				$item['code'] = "qpb_b";
				$item['name'] = "Black Quick Pak Bag";
				$item['cost'] = 19.99;
				$item['quantity'] = $_POST['black'];
				$item['sub'] = $item['cost'] * $item['quantity'];
				
				$order['contents'][] = $item;
			}
			
			if($_POST['blue'] > 0) {
				$item = array();
				
				$item['code'] = "qpb_u";
				$item['name'] = "Blue Quick Pak Bag";
				$item['cost'] = 19.99;
				$item['quantity'] = $_POST['blue'];
				$item['sub'] = $item['cost'] * $item['quantity'];
				
				$order['contents'][] = $item;
			}
			
			if($_POST['pink'] > 0) {
				$item = array();
				
				$item['code'] = "qpb_p";
				$item['name'] = "Pink Quick Pak Bag";
				$item['cost'] = 19.99;
				$item['quantity'] = $_POST['pink'];
				$item['sub'] = $item['cost'] * $item['quantity'];
				
				$order['contents'][] = $item;
			}
			
			if($_POST['camo'] > 0) {
				$item = array();
				
				$item['code'] = "qpb_c";
				$item['name'] = "Camouflage Quick Pak Bag";
				$item['cost'] = 19.99;
				$item['quantity'] = $_POST['camo'];
				$item['sub'] = $item['cost'] * $item['quantity'];
				
				$order['contents'][] = $item;
			}
			
			if($_POST['taupe'] > 0) {
				$item = array();
				
				$item['code'] = "qpb_t";
				$item['name'] = "Taupe Quick Pak Bag";
				$item['cost'] = 19.99;
				$item['quantity'] = $_POST['taupe'];
				$item['sub'] = $item['cost'] * $item['quantity'];
				
				$order['contents'][] = $item;
			}
			
			$_SESSION['order'] = $order;
			
			
			redirect('/baby/');
		}
	}
	
	function bag_check($str) {
		if((int) $this->input->post('black') > 0 || (int) $this->input->post('blue') > 0 || (int) $this->input->post('pink') > 0
			|| (int) $this->input->post('camo') > 0 || (int) $this->input->post('taupe') > 0) {
			return true;
		} else {
			$this->form_validation->set_message('bag_check', "You must purchase at least one bag.");
			return false;
		}
	}
	
	function referral_check($code) {
		if(!empty($code)) {
			//query table, check for code and email
			$query = $this->db->query("SELECT * FROM referral WHERE code='".$code."' AND referrer='".mysql_real_escape_string($this->input->post('email'))."'");
			
			if($query->num_rows() < 1) {
				$this->form_validation->set_message('referral_check', "The entered referral code is not in our records or a non-matching email address was used.");
				return FALSE;
			}
		}
		
		return TRUE;
	}
	
}

//end home