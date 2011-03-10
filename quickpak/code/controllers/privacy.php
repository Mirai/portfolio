<?php

class Privacy extends Controller {

	function index() {
		$data['page'] = "privacy_view";
		
		$this->load->view('main/main_view', $data);
	}
	
} // end service