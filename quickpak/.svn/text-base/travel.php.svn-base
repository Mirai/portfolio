<?php
session_start();
class Travel extends Controller {
	
	function index() {
		if(!isset($_SESSION['order'])) {
			redirect('');
		}
		
		$bag_quantity = 0;
		foreach($_SESSION['order']['contents'] as $item) {
			if(substr($item['code'], 0, 3) == "qpb") {
				$bag_quantity += $item['quantity'];
			}
		}
	
		$data['page'] = "travel_view";
		$data['quantity'] = $bag_quantity;
		
		$this->load->view('main/main_view', $data);
	}
	
	function process() {
		if($_POST['quantity'] > 0) {
			$item = array();
			
			$item['code'] = 'tk';
			$item['name'] = "Travel Kit";
			$item['cost'] = 9.99;
			$item['quantity'] = $_POST['quantity'];
			$item['sub'] = $item['cost'] * $item['quantity'];
		
			$_SESSION['order']['contents'][] = $item;
		}
	
		redirect('/emergency/');
	}
	
}

//end travel