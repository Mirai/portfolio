<?php
require_once(dirname(__FILE__).'/../../../assets/php/smtp_validateEmail.class.php');
class Referral extends Controller {
	function index() {
		$data['page'] = 'referral_view';
		$this->load->view('main/main_view', $data);
	}
	
	function process() {
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('email1', 'First Email', 'required|valid_email|callback_validate_email');
		$this->form_validation->set_rules('email2', 'Second Email', 'required|valid_email|callback_validate_email');
		$this->form_validation->set_rules('email3', 'Third Email', 'required|valid_email|callback_validate_email');
		$this->form_validation->set_rules('email4', 'Fourth Email', 'required|valid_email|callback_validate_email');
		$this->form_validation->set_rules('email5', 'Fifth Email', 'required|valid_email|callback_validate_email');
		
		$this->form_validation->set_rules('name1', 'First Referral Name', 'required');
		$this->form_validation->set_rules('name2', 'Second Referral Name', 'required');
		$this->form_validation->set_rules('name3', 'Third Referral Name', 'required');
		$this->form_validation->set_rules('name4', 'Fourth Referral Name', 'required');
		$this->form_validation->set_rules('name5', 'Fifth Referral Name', 'required');
		
		$this->form_validation->set_rules('referrer', 'Your Email', 'required|valid_email');
		$this->form_validation->set_rules('referrer_name', 'Your Name', 'required');
		
		$this->form_validation->set_rules('email_check', '', 'callback_email_check');
		
		if($this->form_validation->run() == FALSE) {
			$data['page'] = "referral_view";
			
			$this->load->view('main/main_view', $data);
		} else {
			foreach($_POST as $key => $value) {
				$_POST[$key] = mysql_real_escape_string($value);
			}
		
			// generate code
			$character_set_array = array();
			$character_set_array[] = array('count' => 4, 'characters' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');
			$character_set_array[] = array('count' => 4, 'characters' => '0123456789');
			$temp_array = array();
			foreach($character_set_array as $character_set) {
			  for($i = 0; $i < $character_set['count']; $i++) {
				$temp_array[] = $character_set['characters'][rand(0, strlen($character_set['characters']) - 1)];
			  }
			}
			shuffle($temp_array);
			$referral_code = implode('', $temp_array);

			// insert into database
			$insert = array(
				'code' => $referral_code,
				'referrer' => $_POST['referrer']
			);
			
			$this->db->insert('referral', $insert);
			
			// send emails to referrals
			$this->load->library('email');
			
			$config['mailtype'] = 'html';
			$this->email->initialize($config);
			
			$list = array($_POST['email1'], $_POST['email2'], $_POST['email3'], $_POST['email4'], $_POST['email5']);
			
			foreach($list as $address) {
				$this->email->clear();
				
				$this->email->to($address);
				$this->email->from('donotreply@buzybeeinc.com');
				$this->email->subject('Buzy Bee Quick Pak Bag Referral');
				$this->email->message($_POST['referrer_name'].' thought you would be interested in some of our products, please click the link below to see what was purchased.<br />Check out '.base_url().' for more info.');

				$this->email->send();
			}
			
			// send email to referrer
			$this->email->clear();
			
			$this->email->to($_POST['referrer']);
			$this->email->from('donotreply@buzybeeinc.com');
			$this->email->subject('Quick Pak Bag Referral Code');
			$this->email->message('Thank you for your referrals! Your referral code is '.$referral_code.'. Simply go to our website and enter your referral code in the promotional box and proceed with your order. PLEASE NOTE: this code is linked to this email account, make sure to use the same email account when making your next purchase.<br />
			<br />
			Thank you,<br />
			<br />
			TeamOne');
			$this->email->send();
			
			// redirect somewhere
			redirect();
		}
	}
	
	function validate_email($email) {
		$validator = new SMTP_validateEmail();
		
		$results = $validator->validate(array($email));
		
		if(@$results[$email]) {
			return TRUE;
		} else {
			$this->form_validation->set_message('validate_email', 'The %s field does not appear to be a sendable email address.');
			return FALSE;
		}
	}
	
	function email_check($str) {
		$valid = TRUE;
		
		$emails = array(
					$this->input->post('email1'),
					$this->input->post('email2'),
					$this->input->post('email3'),
					$this->input->post('email4'),
					$this->input->post('email5'),
					$this->input->post('referrer')
		);
		
		for($x = 0; $x < count($emails) - 1; $x++) {
			for($y = $x + 1; $y < count($emails); $y++) {
				//echo $emails[$x].' == '.$emails[$y]."<br>";
				if($emails[$x] == $emails[$y]) {
					$valid = FALSE;
				}
			}
		}
		
		if(!$valid) {
			$this->form_validation->set_message('email_check', 'You must submit 5 unique email addresses and not include your own.');
		}
		
		return $valid;
	}
}

// end referral