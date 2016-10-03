<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include APPPATH . '/controllers/common.php';

error_reporting(0);

ini_set('post_max_size', '64M');
ini_set('upload_max_filesize', '64M');

class Category extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->model('category_model', 'categories');
        $this->load->library('form_validation');
        $postdata = file_get_contents("php://input");
        if (($this->input->server('REQUEST_METHOD') == 'POST')) {
            $_POST = (array) json_decode($postdata);
        } else {
            $_GET = (array) json_decode($postdata);
        }
    }

    public function index() {
        $category_lists = $this->categories->category_lists();        
        echo json_encode(array('success' => 'true', 'category_lists' => $category_lists));
    }
    //login
    public function ajax_add() {


        if (($this->input->server('REQUEST_METHOD') == 'POST')) {
            $this->form_validation->set_rules('cate_name', 'Category Name', 'trim|required|min_length[3]|max_length[30]|is_unique[category_mst.cate_name]');
            if ($this->form_validation->run() == FALSE) {

                echo json_encode(array('status' => 0, 'msg' => validation_errors()));
                return false;
            } else {
                $data = array('cate_name' => trim($this->input->post('cate_name'))
                );
                $add_category = $this->categories->save_category($data);
                if ($add_category == 1) {                    
                    echo json_encode(array('status' => 1, 'msg' => ucfirst($this->input->post('cate_name')) . ' Category has been Added Successfully'));
                } else {
                    echo json_encode(array('status' => 0, 'msg' => 'Category Added Not Successfully'));
                }
            }
        }
    }

    public function edit($pk_cat_id = NULL) {
        $pk_cat_id = $this->input->post('pk_cat_id');
        if ($pk_cat_id != "") {
            $get_category_list = $this->categories->get_category_list(md5($pk_cat_id));  
            if (count($get_category_list) > 0) {
                $get_category_list[0]['status'] = 'true';
                echo json_encode($get_category_list[0]);
            }                
            
    }
    }

    //login
    public function ajax_edit() {


        if (($this->input->server('REQUEST_METHOD') == 'POST')) {
            $this->form_validation->set_rules('cate_name', 'Category Name', 'trim|required|min_length[3]|max_length[30]|callback_exist_category_check');
            if ($this->form_validation->run() == FALSE) {

                echo json_encode(array('status' => 0, 'msg' => validation_errors()));
                return false;
            } else {
                $data = array('cate_name' => trim($this->input->post('cate_name'))
                );
                $id = trim($this->input->post('pk_cat_id'));
                $update_category = $this->categories->update_category($data, md5($id));
                if ($update_category == 1) {                    
                   echo json_encode(array('status' => 1, 'msg' => ucfirst($this->input->post('cate_name')) . ' Category has been Updated Successfully'));
                } else {
                    echo json_encode(array('status' => 0, 'msg' => 'Category has not been Updated Successfully'));
                }
            }
        }
    }

    public function exist_category_check() {       

            $check_exist = $this->categories->check_exist_category(trim($this->input->post('cate_name')), trim($this->input->post('pk_cat_id')));
            if (count($check_exist)) {
                $this->form_validation->set_message('exist_category_check', 'Category Name already exists!');
                return FALSE;
            } else {
                return TRUE;
            }
       
    }

    public function delete($pk_cat_id = NULL) {
        if ($pk_cat_id != "") {
            $deleteCategory = $this->categories->delete_category($pk_cat_id);

            if ($deleteCategory == "1") {
                $get_sub_category_list = $this->categories->get_channels($pk_cat_id);
                foreach ($get_sub_category_list as $subcate) {
                    $image_file = "";
                    if ($subcate["channel_logo"] != "") {
                        $image_file = './upload/channel/' . $subcate["channel_logo"];

                        if (file_exists($image_file)) {
                            unlink($image_file);
                        }
                    }
                }
                $delete_sub_ategory = $this->categories->delete_channel_list($pk_cat_id);


                $this->session->set_flashdata('SucMessage', 'Category has been deleted successfully!!!');
            } else {
                $this->session->set_flashdata('ErrorMessages', 'Category has not been deleted successfully!!!');
            }
            redirect(base_url() . 'admin/category/', 'refresh');
        } else {
            redirect(base_url() . 'admin/category/', 'refresh');
        }
    }

    public function change_category_active() {
        if (($this->input->server('REQUEST_METHOD') == 'POST')) {

            $data = array('standing' => trim($this->input->post('standing'))
            );
            $id = trim($this->input->post('pk_cat_id'));
            $update_category = $this->categories->update_category($data, $id);
            $standing=($this->input->post('standing')==1 ? 'Active' : 'Inactive');
            if ($update_category == 1) {                
                echo json_encode(array('status' => 1, 'msg' => "Category $standing Successfully"));
            } else {
                echo json_encode(array('status' => 0, 'msg' => "Category $standing Not Successfully"));
            }
        }
    }

    public function channel_list() {
        $channel_lists = $this->categories->channel_lists();
        echo json_encode(array('success' => 'true','channel_lists' => $channel_lists));       
    }

    public function channel_add() {
        $category_lists = $this->categories->category_lists();
        echo json_encode(array('success' => 'true','category_lists' => $category_lists));   
    }

    //login
    public function ajax_add_channel() { 


        if (($this->input->server('REQUEST_METHOD') == 'POST')) {
            
            //echo "<pre>";print_r($_POST);die;
            $this->form_validation->set_rules('fk_cat_id', 'Category Name', 'trim|required');
            $this->form_validation->set_rules('channel_name', 'Channel Name', 'trim|required|min_length[3]|max_length[30]');
            $this->form_validation->set_rules('channel_no', 'Channel Number', 'trim|required|min_length[3]|max_length[30]');
            $this->form_validation->set_rules('channel_url', 'Channel URL', 'trim|required|min_length[3]|max_length[150]');
            if ($this->form_validation->run() == FALSE) {

                echo json_encode(array('status' => 0, 'msg' => validation_errors()));
                return false;
            }
            if (empty($_POST['channel_logo']->filename)) {
                echo json_encode(array('status' => 0, 'msg' => "Please upload channel logo"));
            } else {
                    $filename="";
                    if (isset($_POST['channel_logo']->filename)) {
                    if (isset($_POST['channel_logo']->base64)) {                        
                    $storeFolder = "./upload/channel";
                    if (!is_dir($storeFolder)) {
                        mkdir($storeFolder, 0777, TRUE);
                    }
                    if (!is_dir($storeFolder)) {
                        mkdir($storeFolder, 0777, TRUE);
                    }
                    $filename = md5(time()) . '-' . $_POST['channel_logo']->filename;

                    if (isset($_POST['channel_logo']->base64) && !empty($_POST['channel_logo']->base64)) {                                       
                        $image_data = base64_decode($_POST['channel_logo']->base64);
                        file_put_contents($storeFolder . '/' . $filename . '', $image_data);                   
                    }
                }
                }
                
                if ($filename == "") {
                    echo json_encode(array('status' => 0, 'msg' => "<p>Please upload only image</p>"));
                }
                else {
                    $data = array('fk_cat_id' => trim($this->input->post('pk_cat_id')),
                        'channel_name' => trim($this->input->post('channel_name')),
                        'channel_no' => trim($this->input->post('channel_no')),
                        'channel_url' => trim($this->input->post('channel_url')),
                        'channel_logo' => trim($filename)
                    );
                    $add_channel = $this->categories->save_channel($data);
                    if ($add_channel == 1) {                        
                        echo json_encode(array('status' => 1, 'msg' => ucfirst($this->input->post('channel_name')) . 'Channel has been added Successfully'));
                    } else {
                        echo json_encode(array('status' => 0, 'msg' => 'Channel has not been added Successfully'));
                    }
                }
            }
        }
    }

    public function channel_edit($pk_ch_id = NULL) {
        $pk_ch_id = $this->input->post('pk_ch_id');
        if ($pk_ch_id != "") {
            $category_lists = $this->categories->category_lists();
            $get_channel_list = $this->categories->get_channel_list(md5($pk_ch_id));
            if (count($get_channel_list) > 0) {
                $get_channel_list[0]['status'] = 'true';
                echo json_encode($get_channel_list[0]);                
            }
            
        }
    }

    public function ajax_edit_channel() {


        if (($this->input->server('REQUEST_METHOD') == 'POST')) {            
            $this->form_validation->set_rules('pk_cat_id', 'Category Name', 'trim|required');
            $this->form_validation->set_rules('channel_name', 'Channel Name', 'trim|required|min_length[3]|max_length[30]');
            $this->form_validation->set_rules('channel_no', 'Channel Number', 'trim|required|min_length[3]|max_length[30]');
            $this->form_validation->set_rules('channel_url', 'Channel URL', 'trim|required|min_length[3]|max_length[150]');
            if ($this->form_validation->run() == FALSE) {

                echo json_encode(array('status' => 0, 'msg' => validation_errors()));
                return false;
            } else {

                $id = trim($this->input->post('pk_ch_id'));
                $get_channel_list = $this->categories->get_channel_list($id);
                if (isset($_FILES['channel_logo']['name']) && (!empty($_FILES['channel_logo']['name']))) {
                    $upload_image = $this->do_upload_image('channel_logo');
                    if ($upload_image['image_message'] == "success") {

                        if ($get_channel_list[0]["channel_logo"] != "") {
                            $image_file = './upload/channel/' . $get_channel_list[0]["channel_logo"];
                            if (file_exists($image_file)) {
                                unlink($image_file);
                            }
                        }

                        $file_name = trim($upload_image['image_file_name']);
                    } else {
                        echo json_encode(array('status' => 0, 'msg' => "<p>Please upload only image</p>"));
                        return false;
                    }
                } else {
                    $file_name = $get_channel_list[0]['channel_logo'];
                }
                $data = array('fk_cat_id' => trim($this->input->post('pk_cat_id')),
                    'channel_name' => trim($this->input->post('channel_name')),
                    'channel_no' => trim($this->input->post('channel_no')),
                    'channel_url' => trim($this->input->post('channel_url')),
                    'channel_logo' => trim($file_name)
                );

                $update_channel = $this->categories->update_channel($data, $id);
                if ($update_channel == 1) {
                    $this->session->set_flashdata('SucMessage', ucfirst($this->input->post('channel_name')) . ' Channel Updated Successfully');
                    echo json_encode(array('status' => 1));
                } else {
                    echo json_encode(array('status' => 0, 'msg' => 'Channel Updated Not Successfully'));
                }
            }
        }
    }

    public function exist_channel_check() {
        if (($this->input->server('REQUEST_METHOD') == 'POST')) {

            $check_exist = $this->categories->check_exist_sub_category(trim($this->input->post('channel_name')), trim($this->input->post('fk_cat_id')), trim($this->input->post('pk_ch_id')));
            if (count($check_exist)) {
                echo "1";
            } else {
                echo "0";
            }
        }
    }

    public function delete_channel($pk_ch_id = NULL) {
        if ($pk_ch_id != "") {
            $get_channel_list = $this->categories->get_channel_list($pk_ch_id);
            if (count($get_channel_list) > 0) {

                if ($get_channel_list[0]["channel_logo"] != "") {
                    $image_file = './upload/channel/' . $get_channel_list[0]["channel_logo"];

                    if (file_exists($image_file)) {
                        unlink($image_file);
                    }
                }

                $deleteSubCategory = $this->categories->delete_channel($pk_ch_id);
                if ($deleteSubCategory == "1") {
                    $this->session->set_flashdata('SucMessage', 'Channel has been deleted successfully!!!');
                } else {
                    $this->session->set_flashdata('ErrorMessages', 'Channel has not been deleted successfully!!!');
                }
            }
            redirect(base_url() . 'admin/category/channel_list', 'refresh');
        } else {
            redirect(base_url() . 'admin/category/channel_list', 'refresh');
        }
    }
    
     public function change_channel_active() {
        if (($this->input->server('REQUEST_METHOD') == 'POST')) {

            $data = array('standing' => trim($this->input->post('standing'))
            );
            $id = trim($this->input->post('pk_ch_id'));
             $update_channel = $this->categories->update_channel($data, $id);
            $standing=($this->input->post('standing')==1 ? 'Active' : 'Inactive');
            if ($update_channel == 1) {                
                echo json_encode(array('status' => 1, 'msg' => "Channel $standing Successfully"));
            } else {
                echo json_encode(array('status' => 0, 'msg' => "Channel $standing Not Successfully"));
            }
        }
    }

    function do_upload_image($field_name) {
        $msg = array();
        $file_name = "";
        $message = "";
        $image_new_name = time() . "-" . $field_name;
        $config['upload_path'] = './upload/channel/';
        $config['upload_url'] = base_url() . "upload/channel/";
        $config['allowed_types'] = "gif|jpg|png|jpeg";
        $config['file_name'] = $image_new_name;
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($field_name)) {
            $error = array('error' => $this->upload->display_errors());
            $message = $error['error'];
        } else {
            $data = array('upload_data' => $this->upload->data());
            $file_name = $data['upload_data']['orig_name'];
            $message = "success";
        }
        $msg = array("image_message" => $message, "image_file_name" => $file_name);
        return $msg;
    }

}
