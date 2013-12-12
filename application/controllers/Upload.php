<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Upload extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url','file'));
    }

    public function index() {
        $this->load->view('admin/upload', array('error' => ''));
    }

    public function do_upload() {
        $upload_path_url = base_url() . 'upload_files/activity/';

        $config['upload_path'] = FCPATH . 'upload_files/activity/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif|mp3|avi|mp4|wmv';
        $this->load->library('upload', $config);
        $field_name = "userfile";
        $_FILES['userfile']['name']=iconv("utf-8","gbk",$_FILES['userfile']['name']); 
        if (!$this->upload->do_upload($field_name)) {
            $error = array('error' => $this->upload->display_errors());
            print_r($error);
        } else {
           
            $data = $this->upload->data();
            $config = array();
            $config['image_library'] = 'gd2';
            $config['source_image'] = $data['full_path'];
            $config['create_thumb'] = TRUE;
            $config['new_image'] = $data['file_path'] . 'thumbs/';
            $config['maintain_ratio'] = TRUE;
            $config['thumb_marker'] = '';
            $config['width'] = 75;
            $config['height'] = 50;
            $this->load->library('image_lib', $config);
            $this->image_lib->resize();

            $address='/upload_files/activity/'.$data['client_name'];
            $this->load->model('uploadres_model','uploadres');
            $name=iconv("gbk","utf-8",$data['client_name']);
            $theme=urldecode($this->input->post('theme'));
            $custom_theme=urldecode($this->input->post('custom_theme'));
            $level=urldecode($this->input->post('level'));
            $description=urldecode($this->input->post('description'));
            $keyword=urldecode($this->input->post('keyword'));
            $keyphrase=urldecode($this->input->post('keyphrase'));
            $author=$this->session->userdata('realname');
            $author_group=$this->session->userdata('grade');
            $file_size=$data['file_size'];
            $judge=$this->uploadres->insert($name,$theme,$custom_theme,$level,$description,$keyword,$keyphrase,date("Y-m-d   H:i:s"),$address,$author,$author_group,$file_size);
            /*   if($judge==true){
                $result=100;
            }else{
                $result=102;
            }*/
            //set the data for the json array
            $info['size'] = $data['file_size'];
            $info['type'] = $data['file_type'];
            $info['url'] = $upload_path_url . $data['file_name'];
            // I set this to original file since I did not create thumbs.  change to thumbnail directory if you do = $upload_path_url .'/thumbs' .$data['file_name']
            $info['thumbnailUrl'] = $upload_path_url . 'thumbs/' . $data['file_name'];
            $info['deleteUrl'] = base_url() . 'upload/deleteImage/' . $data['file_name'];
            $info['deleteType'] = 'DELETE';
            $info['error'] = null;

            $files[] = $info;
            //this is why we put this in the constants to pass only json data
            if (IS_AJAX) {
                echo json_encode(array("files" => $files));
            } else {
                $file_data['upload_data'] = $this->upload->data();
                $this->load->view('upload/upload_success', $file_data);
            }
        }
    }

    public function deleteImage($file) {//gets the job done but you might want to add error checking and security
        $success = unlink(FCPATH . 'uploads/' . $file);
        $success = unlink(FCPATH . 'uploads/thumbs/' . $file);
        //info to see if it is doing what it is supposed to 
        $info->sucess = $success;
        $info->path = base_url() . 'uploads/' . $file;
        $info->file = is_file(FCPATH . 'uploads/' . $file);

        if (IS_AJAX) {
            //I don't think it matters if this is set but good for error checking in the console/firebug
            echo json_encode(array($info));
        } else {
            //here you will need to decide what you want to show for a successful delete        
            $file_data['delete_data'] = $file;
            $this->load->view('admin/delete_success', $file_data);
        }
    }

    public function findAllRes(){            
        $author_group=$this->session->userdata('grade');
        $this->load->model('uploadres_model','uploadres');
        $judge=$this->uploadres->search($author_group);
        //print_r($judge);
        foreach ($judge as $row) {
        if(abs($row->file_size)>=1000){         
        $row->file_size=$row->file_size/1000;
        $row->file_size=$row->file_size.'MB';
        }else{
        $row->file_size=$row->file_size.'KB';
        }
        }
      
        $data['data']=$judge;
        print_r(json_encode($data));
    }
   

}