<?php
defined('BASEPATH') or exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class Welcome extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Authorization_Token');
        // $this->load->model('user_model');
    }
    public function index_get()
    {
        $array = [
            'status' => true,
            'message' => 'Welcome to REST API With JWT Token',
			'author' => 'Mochamad Rizal Fachrudin',
			'assignment' => 'Tugas Mentoring 15'
        ];
        
        $this->response($array, REST_Controller::HTTP_OK); 
    }
}
