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
        $judge=true;
        $resource='';
        $resource='';
        $res_type='';
        $res_name='';
        if($rid!=0){
            $info=$this->uploadres->findById($rid);
            $resource=$info->address;
            $res_type=$info->file_type;
            $res_name=$info->name;
            $judge=$this->activity->insert($title,$content,$goal,$type,$level,$theme,$resource,$author,$author_id,$author_group);
        }else{
            $judge=$this->activity->insert($title,$content,$goal,$type,$level,$theme,$resource,$author,$author_id,$author_group);
        }
        
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
        $author_group=$this->session->userdata('grade');
        $this->load->model('activity_model','activity');
        $judge=$this->activity->findAll($author_group);    
        if($judge==null){
            $result=102;
        }else{
            $result=100;
            $info=array();
            $tid=$this->session->userdata('tid');
            for($i=0;$i<count($judge);$i++){
                $judge[$i]=(array)$judge[$i];
                if($judge[$i]['author_id']==$tid){
                    $delete = array('delete' =>1);
                }else{
                    $delete = array('delete' =>0);
                }
                $judge[$i]=array_merge($judge[$i],$delete);

            }


        } 
        $data['errcode']=$result;       
        $data['data']=$judge;
        print_r(json_encode($data));
    }
    public function findByAid($aid){
        $this->load->model('activity_model','activity');
        $judge=$this->activity->findById($aid);
        $this->load->model('uploadres_model','uploadres');
        $info=$this->uploadres->findByPath($judge->resource);
        print_r($info);
        if($judge==null){
            $result=102;
        }else{
            $result=100;

        }
        $data['errcode']=$result;
        $data['data']=array('title' =>$judge->title , 'content' =>$judge->content,'goal' =>$judge->goal,
            'type' =>$judge->type,'theme' =>$judge->theme,'author' =>$judge->author,
            'author_group' =>$judge->author_group,'res_address'=>$judge->resource,'res_type'=>$info->file_type,'res_name'=>$info->name);
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
        return $count;

    }


    public function intelligentPush(){
        $sid=$this->session->userdata('sid');
        $author_group=$this->session->userdata('grade');
        $this->load->model('activity_model','activity');
        $allactivity=$this->activity->findAll($author_group);
        $allactivity=(array)$allactivity;
        $this->load->model('personal_activity_model','pa');
        $aid=$this->pa->find_aid_teacherPush($sid);
        for($i=0;$i<count($aid);$i++){
            foreach ($allactivity as $row) {
                $max=$sum=0;
                $right_act=$row;
                if($aid[$i]->aid==$row->id){
                    $sum=0;
                }else{
                    $act=$this->activity->findById($aid[$i]->aid);
                    similar_text($act->title, $row->title, $percent);
                    if($percent>90){
                        $sum+=2;
                    }else if($percent>50&&$percent<90){
                        $sum+=1;
                    }
                    if($row->resource!==""){   
                        $this->load->model('testresult_model','testresult');
                        $f_style=$this->testresult->findBySid($sid)->first_style;
                        $s_style=$this->testresult->findBySid($sid)->second_style;
                        $this->load->model('uploadres_model','uploadres');
                        $res=$this->uploadres->findByPath($row->resource);
                        $res_type=$res->file_type;
                        if($f_style=='活跃型'&&$s_style=='视觉型'&&$res_type=='video'){
                            $sum+=10;
                        }else if($f_style=='活跃型'&&$s_style=='言语型'&&$res_type=='video'){
                            $sum+=10;
                        }else if($f_style=='沉思型'&&$s_style=='视觉型'&&$res_type=='video'){
                            $sum+=10;
                        }else if($f_style=='沉思型'&&$s_style=='视觉型'&&$res_type=='video'){
                            $sum+=10;
                        }
                    }   
                    if($act->goal==$row->goal){
                        $sum+=2;
                    }
                    if($act->theme==$row->theme){
                        $sum+=2;
                    }
                    if($act->level==$row->level){
                        $sum+=2;
                    }
                    if($act->type==$row->type){
                        $sum+=2;
                    }    
                }
                if($sum>$max){
                    $max=$sum;
                    $right_act=$row;
                    }
                print_r($sum);
            }
            $right[$i]=$right_act;    
        }
        print_r($right);
    }

  
    

  

}
?>