<?php
defined('BASEPATH') or exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class Login extends REST_Controller
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
            'message' => 'Hello World!'
        ];
        
        $this->response($array, REST_Controller::HTTP_OK); 
    }
}
