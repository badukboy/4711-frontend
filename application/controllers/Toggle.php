<?php

/**
 * Description of Toggle
 */
class Toggle extends Application {
	public function index()	{
                $this->load->helper('form');
                $this->load->helper('url');
                
		$origin = $_SERVER['HTTP_REFERER'];
		$role = $this->session->userdata('userrole');
		if ($role == 'user') $role = 'admin';
                else if ($role == 'guest') $role = 'user';
		else $role = 'guest';
                $this->session->set_userdata('userrole', $role);
                redirect($origin);
	}
}
