<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activity extends MY_Controller {
    public function activityAction(){

    }

    public function addActivity(){
        $this->load->model('uploadres_model','uploadres');
        $this->load->model('activity_model','activity');
        $title=urldecode($this->input->post('title'));
        $content=urldecode($this->input->post('content'));
        $goal=urldecode($this->input->post('goal'));
        $type=urldecode($this->input->post('type'));
        $level=$this->input->post('level');
        $theme=urldecode($this->input->post('theme'));
        $author=$this->session->userdata('realname');
        $author_id=$this->session->userdata('tid');
        $author_group=$this->session->userdata('grade');
        $rid=$this->input->post('rid');
        if($rid!=0){
            $info=$this->uploadres->findById($rid);
        }
        $resource=$info->address;
        $res_type=$info->file_type;
        $res_name=$info->name;
        $judge=$this->activity->insert($title,$content,$goal,$type,$level,$theme,$resource,$author,$author_id,$author_group);
        if($judge==true){
            $result=100;
        }else{
            $result=102;
        }
        $data['data']=array('title' =>$title , 'content' =>$content,'goal' =>$goal,'type' =>$type,'theme' =>$theme,'author' =>$author,
            'author_group' =>$author_group,'res_address'=>$resource,'res_type'=>$res_type,'res_name'=>$res_name);
        $data['errcode']=$result;
        print_r(json_encode($data));

    }

    public function findAllAcitvity(){
        $studentCount=$this->studentCount();
        $author_group=$this->session->userdata('grade');
        $this->load->model('activity_model','activity');
        $this->activity->update_studentcount($aid,$studentCount);
        $judge=$this->activity->findAll($author_group);    
        if($judge==null){
            $result=102;
        }else{
            $result=100;
        } 
        $data['errcode']=$result;       
        $data['data']=$judge;
        print_r(json_encode($data));
    }

    public function deleteActivity(){
        $aid=$this->input->post('aid');
        $this->load->model('activity_model','activity');
        $realname=$this->session->userdata('realname');
        $info=$this->activity->findById($aid);
        $author=$info->author;
        if($realname==$author){
            $judge=$this->activity->delete($aid);
            if($judge==true){
                $result=100;
            }else{
                $result=102;
            }
        }else{
                $result=103;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));

    }

    public function studentCount(){
        $aid=$this->input->post('aid');
        $this->load->model('personal_activity_model','pa');
        $count=$this->pa->studentCount($aid);
        $data['data']=$count;
        print_r($data);

    }
  
    

  

}
?>