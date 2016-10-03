<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include APPPATH . '/controllers/common.php';

//error_reporting(E_PARSE);
class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Loading report_model 
        $this->load->model('login_model', 'login');
        // Loading form_validation library files
        $this->load->library('form_validation');
        $postdata = file_get_contents("php://input");
        if (($this->input->server('REQUEST_METHOD') == 'POST')) {            
            $_POST = (array) json_decode($postdata);
        } else {
         $_GET = (array) json_decode($postdata);   
        }
    }

    //login
    public function ajax_login() {


        if (($this->input->server('REQUEST_METHOD') == 'POST')) {
            
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                echo json_encode(array('status' => 0, 'msg' => validation_errors()));
                return false;
            } else {
                $row = $this->login->login_verify(trim($this->input->post('email')), trim($this->input->post('password')));
                if (count($row) == 1) {
                    echo json_encode(array('status' => 1, 'msg' => $row));
                } else {
                    echo json_encode(array('status' => 0, 'msg' => 'Invalid Credential'));
                }
            }
        }
    }

}
