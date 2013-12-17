<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activity extends MY_Controller {
    public function activityAction(){

    }

    public function addActivity(){
        $this->load->model('uploadres_model','uploadres')
        $title=$this->input->post('title');
        $content=$this->input->post('content');
        $goal=$this->input->post('goal');
        $type=$this->input->post('type');
        $level=$this->input->post('level');
        $theme=$this->input->post('theme');
        $resource=$this->uploadres->findById($this->input->post('rid'))->address;
    }
  
    

  

}
?>