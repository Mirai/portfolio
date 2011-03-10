<?php
session_start();
class Emergency extends Controller {
	
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
	
		$data['page'] = "emergency_view";
		$data['quantity'] = $bag_quantity;
		
		$this->load->view('main/main_view', $data);
	}
	
	function process() {
		if($_POST['quantity'] > 0) {
			$item = array();
			
			$item['code'] = 'fak';
			$item['name'] = "First Aid Kit";
			$item['cost'] = 6.99;
			$item['quantity'] = $_POST['quantity'];
			$item['sub'] = $item['cost'] * $item['quantity'];
		
			$_SESSION['order']['contents'][] = $item;
		}
	
		redirect('/orderprocess/');
	}
	
}

//end emergency