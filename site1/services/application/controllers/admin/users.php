<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include APPPATH . '/controllers/common.php';

//error_reporting(E_PARSE);
class Users extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->model('users_model', 'users');
        $this->load->library('form_validation');
        $postdata = file_get_contents("php://input");
        if (($this->input->server('REQUEST_METHOD') == 'POST')) {
            $_POST = (array) json_decode($postdata);
        } else {
            $_GET = (array) json_decode($postdata);
        }
    }

    public function index() {
        $user_list = $this->users->user_lists($_POST);
        echo json_encode(array('status'=>'true','response'=>$user_list));
    }

    public function ajax_add() {


        if (($this->input->server('REQUEST_METHOD') == 'POST')) {
            $this->form_validation->set_rules('fname', 'First Name', 'trim|required|min_length[3]|max_length[30]');
            $this->form_validation->set_rules('lname', 'Last Name', 'trim|required|min_length[3]|max_length[20]');
            $this->form_validation->set_rules('emailid', 'Email', 'trim|required|valid_email|is_unique[cust_mst.emailid]');
            $this->form_validation->set_rules('mobileno', 'Mobile Number', 'trim|required|min_length[10]|max_length[10]|numeric');
            $this->form_validation->set_rules('vc_number', 'VC Number', 'trim|required|min_length[3]|max_length[30]|is_unique[cust_mst.vc_number]');
            if ($this->form_validation->run() == FALSE) {

                echo json_encode(array('status' => 0, 'msg' => validation_errors()));
                return false;
            } else {
                $data = array('fname' => trim($this->input->post('fname')),
                    'lname' => trim($this->input->post('lname')),
                    'emailid' => trim($this->input->post('emailid')),
                    'mobileno' => trim($this->input->post('mobileno')),
                    'vc_number' => trim($this->input->post('vc_number'))
                );
                $add_users = $this->users->save_users($data);
                if ($add_users == 1) {
                    //$this->session->set_flashdata('SucMessage', ucfirst($this->input->post('fname')) . ' User Added Successfully');
                    echo json_encode(array('status' => 1, 'msg' => ucfirst($this->input->post('fname')) . ' User has been Added Successfully'));
                } else {
                    echo json_encode(array('status' => 0, 'msg' => 'User has not been Added Successfully'));
                }
            }
        }
    }

    public function edit() {
        $pk_cust_id = $this->input->post('pk_cust_id');
        if ($pk_cust_id != "") {
            $get_user_list = $this->users->get_user_list(md5($pk_cust_id));
            if (count($get_user_list) > 0) {
                $get_user_list[0]['status'] = 'true';
                echo json_encode($get_user_list[0]);
            }
        }
    }

    public function ajax_edit() {

        if (($this->input->server('REQUEST_METHOD') == 'POST')) {
            $this->form_validation->set_rules('fname', 'First Name', 'trim|required|min_length[3]|max_length[30]');
            $this->form_validation->set_rules('lname', 'Last Name', 'trim|required|min_length[3]|max_length[20]');
            $this->form_validation->set_rules('emailid', 'Email', 'trim|required|valid_email|callback_exist_email_check');
            $this->form_validation->set_rules('mobileno', 'Mobile Number', 'trim|required|min_length[10]|max_length[10]');
            $this->form_validation->set_rules('vc_number', 'VC Number', 'trim|required|min_length[3]|max_length[30]|callback_exist_vcnumber_check');
            if ($this->form_validation->run() == FALSE) {

                echo json_encode(array('status' => 0, 'msg' => validation_errors()));
                return false;
            } else {
                $data = array('fname' => trim($this->input->post('fname')),
                    'lname' => trim($this->input->post('lname')),
                    'emailid' => trim($this->input->post('emailid')),
                    'mobileno' => trim($this->input->post('mobileno')),
                    'vc_number' => trim($this->input->post('vc_number'))
                );
                $id = trim($this->input->post('pk_cust_id'));
                $update_users = $this->users->update_users($data, md5($id));
                if ($update_users == 1) {
                    echo json_encode(array('status' => 1, 'msg' => ucfirst($this->input->post('fname')) . ' User has been Updated Successfully'));
                } else {
                    echo json_encode(array('status' => 0, 'msg' => 'User has not been Updated Successfully'));
                }
            }
        }
    }

    public function exist_email_check() {           
            $check_exist = $this->users->check_exist_email(trim($this->input->post('emailid')), trim($this->input->post('pk_cust_id')));
            if (count($check_exist)) {
                $this->form_validation->set_message('exist_email_check', 'Email already exists!');
                return FALSE;
            } else {
                return TRUE;
            }
        
    }

    public function exist_vcnumber_check() {
        if (($this->input->server('REQUEST_METHOD') == 'POST')) {

            $check_exist = $this->users->check_exist_vcnumber(trim($this->input->post('vc_number')), trim($this->input->post('pk_cust_id')));
            if (count($check_exist)) {
                $this->form_validation->set_message('exist_vcnumber_check', 'VC Number already exists!');
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

    public function delete($pk_cust_id = NULL) {
        if ($pk_cust_id != "") {
            $deleteUsers = $this->users->delete_user($pk_cust_id);
            if ($deleteUsers == "1") {
                $this->session->set_flashdata('SucMessage', 'User has been deleted successfully!!!');
            } else {
                $this->session->set_flashdata('ErrorMessages', 'User has not been deleted successfully!!!');
            }
            redirect(base_url() . 'admin/users/', 'refresh');
        } else {

            redirect(base_url() . 'admin/users/', 'refresh');
        }
    }

    public function change_users_active() {
        if (($this->input->server('REQUEST_METHOD') == 'POST')) {

            $data = array('standing' => trim($this->input->post('standing'))
            );
            $id = trim(md5($this->input->post('pk_cust_id')));
            $update_users = $this->users->update_users($data, $id);
            $standing = ($this->input->post('standing') == 1 ? 'Active' : 'Inactive');
            if ($update_users == 1) {
                echo json_encode(array('status' => '1', 'reponse' => "User $standing Successfully"));
            } else {
                echo json_encode(array('status' => '0', 'reponse' => "User $standing Not Successfully"));
            }
        }
    }

    public function edit_profile() {
        $get_user_list = $this->users->get_user_mst();
        $data = array('get_user_list' => $get_user_list);
        $this->load->view('admin/includes/header');
        $this->load->view('admin/includes/sidebar');
        $this->load->view('admin/users/edit_profile', $data);
        $this->load->view('admin/includes/footer');
    }

    public function ajax_profile_edit() {


        if (($this->input->server('REQUEST_METHOD') == 'POST')) {
            $this->form_validation->set_rules('fname', 'First Name', 'trim|required|min_length[3]|max_length[30]');
            $this->form_validation->set_rules('lname', 'Last Name', 'trim|required|min_length[3]|max_length[20]');
            $this->form_validation->set_rules('emailid', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('mobileno', 'Mobile Number', 'trim|required|min_length[10]|max_length[10]');

            if ($this->form_validation->run() == FALSE) {

                echo json_encode(array('status' => 0, 'msg' => validation_errors()));
                return false;
            } else {
                $data = array('fname' => trim($this->input->post('fname')),
                    'lname' => trim($this->input->post('lname')),
                    'emailid' => trim($this->input->post('emailid')),
                    'mobileno' => trim($this->input->post('mobileno'))
                );
                if (trim($this->input->post('secret_pass'))) {
                    $data['secret_pass'] = trim(AES_Encode($this->input->post('secret_pass')));
                }
                $id = $this->session->userdata('pk_uid');
                $update_users = $this->users->update_users_mst($data, $id);
                if ($update_users == 1) {
                    $this->session->set_flashdata('SucMessage', ucfirst($this->input->post('fname')) . ' Profile Updated Successfully');
                    echo json_encode(array('status' => 1));
                } else {
                    echo json_encode(array('status' => 0, 'msg' => 'Profile Updated Not Successfully'));
                }
            }
        }
    }

}
