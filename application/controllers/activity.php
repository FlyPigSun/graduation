<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activity extends MY_Controller {

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
        if($judge!=null){
            $result=100;
        }else{
            $result=102;
        }
        if($judge->resource==""){
            $info='';
        }else{
            $info=$this->uploadres->findByPath($judge->resource);
        }
        $this->load->model('personal_activity_model','pa');
        $studentCount=$this->pa->studentCount($aid);
        if($studentCount==0){
            $sInfo='';
        }else{
            for($i=0;$i<$studentCount;$i++){
                $sInfo[]=array('name'=>$this->pa->findByAid($aid)[$i]->realname,
                        'sid'=>$this->pa->findByAid($aid)[$i]->sid,'is_finish'=>$this->pa->findByAid($aid)[$i]->is_finish);
            }   
        }
        $data['errcode']=$result;
        $data['data']=array(
            'title' =>$judge->title ,
            'content' =>$judge->content,
            'goal' =>$judge->goal,
            'type' =>$judge->type,
            'theme' =>$judge->theme,
            'author' =>$judge->author,
            'author_group' =>$judge->author_group,
            'res_address'=>$judge->resource,
            'res_info'=>$info,
            'level'=>$judge->level,
            'sInfo'=>$sInfo,
            'sCount'=>$studentCount,
            );
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
        $this->load->helper('date');
        $aid=$this->pa->find_aid_teacherPush($sid,date("Y-m-d   H"));
        foreach ($allactivity as $row){
            $max=$sum=0;
            if($aid->aid==$row->id){
                $sum=0;
            }else{
                $act=$this->activity->findById($aid->aid);
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
                    if(strpos($f_style, '活跃型') !== false&&strpos($s_style, '言语型') !== false&&$row->type=='小组合作'){
                        $sum+=10;
                    }else if(strpos($f_style, '活跃型') !== false&&strpos($s_style, '视觉型') !== false&&$res_type=='video'){
                        $sum+=10;
                    }else if(strpos($f_style, '沉思型') !== false&&strpos($s_style, '言语型') !== false&&$row->type=='小组合作'){
                        $sum+=10;
                    }else if(strpos($f_style, '沉思型') !== false&&strpos($s_style, '视觉型') !== false&&$res_type=='video'){
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
                $n=$row; 
                $right_act[]=array('sum'=>$sum,'activity'=>$n);
                }        
        }
        foreach ($right_act as $key => $value) {
            $SUM[$key] = $value['sum'];
        }
        array_multisort($SUM,SORT_DESC, $right_act); 
        if(count($right_act)>2){
            for($i=0;$i<3;$i++){
                $activity[$i]=$right_act[$i];
            }
        }else{
            $activity=$right_act;
        }
        print_r($activity);
    }

    public function activityAction($aid){
        $role=$this->session->userdata('role');
        if($role=='student'){
            $sid=$this->session->userdata('sid');
            $this->load->model('personal_activity_model','pa');
            $p_info=$this->pa->find($sid,$aid);
            $isFinish=$p_info->is_finish;
            $answer=$p_info->s_answer;
        }else{
            $isFinish='';
            $answer='';
        }
        $this->load->model('activity_model','activity');
        $info=$this->activity->findById($aid);
        $this->twig->render('activity.html.twig', array('aid' =>$info->id , 'title' =>$info->title ,
            'goal' =>$info->goal ,'type' =>$info->type ,'level' =>$info->level ,'theme' =>$info->theme ,
            'resource' =>$info->resource ,'author' =>$info->author ,'author_id' =>$info->author_id,
            'author_group' =>$info->author_group ,'student_count' =>$info->student_count,'content'=>$info->content,'role'=>$role,
            'isFinish'=>$isFinish,'answer'=>$answer));
    }

    public function studentAnswer(){
        $sid=$this->session->userdata('sid');
        $aid=$this->input->post('aid');
        $s_answer=urldecode($this->input->post('answer'));
        $s_annex='';
        $this->load->model('personal_activity_model','pa');
        $judge=$this->pa->s_update($sid,$aid,$s_answer,$s_annex);
        if($judge==true){
            $result=100;
        }else{
            $result=102;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));
    }
    public function showAnswerAction($aid,$sid){
        $this->load->model('personal_activity_model','pa');
        $info=$this->pa->findInfo($aid,$sid);
        $info=(array)$info;
        $this->twig->render('student_activity_answer.html.twig',$info);

    }
}
?>