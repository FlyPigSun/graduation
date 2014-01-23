<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Teacher_review extends MY_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('teacher_review_model','teacher_review');
    }
    public function addReview($aid){
        $tid=$this->session->userdata('tid');
        $this->load->model('teacher_review_model','teacher_review');
        $theme_match_degree=$this->input->post('theme_match_degree');
        $res_match_degree=$this->input->post('res_match_degree');
        $audio_clear_degree=$this->input->post('audio_clear_degree');
        $voice_fluent_degree=$this->input->post('voice_fluent_degree');
        $class_effect_degree=$this->input->post('class_effect_degree');
        $judge=$this->teacher_review->insert($tid,$aid,$theme_match_degree,$res_match_degree,$audio_clear_degree,
                $voice_fluent_degree,
                $class_effect_degree,1);
        if($judge==true){
            $result=100;
        }else{
            $result=102;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));
    }

    public function showReview($aid){
        $this->load->model('teacher_review_model','teacher_review');
        $info=$this->teacher_review-> findByAid($aid);
        if($info=null){
            $isDo=array('isDo' =>0);
        }else{
            $isDo=array('isDo' =>0);
        }
        $result=100;
        $data['errcode']=$result;
        $data['data']=array_merge(array($info),$isDo);
        print_r(json_encode($data));
    }

}
?>