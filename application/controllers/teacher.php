<?php
ini_set('date.timezone','Asia/Shanghai');
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Teacher extends MY_Controller {
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */ 
    public function register(){   
        $username=$this->input->post('username');
        $password=$this->input->post('password');
        $realname=urldecode($this->input->post('realname'));
        $gender=urldecode($this->input->post('gender'));
        $grade=urldecode($this->input->post('grade'));
        $teachernumber=$this->input->post('teachernumber');
        $this->load->model('teacher_model','teacher');
        $avatar="/upload_files/teacher/avatars/default_avatar.jpg";

        $judge=$this->teacher->find($username);        
        if($judge==null){
            $this->teacher->insert($username,$password,$realname,$gender,$teachernumber,$grade,$avatar);
            $this->teacher->login($username,$password,date("Y-m-d   H:i:s"));
            $arr=array("tid"=>$this->teacher->id,"username"=>$username,
            "time"=>$this->teacher->logintime,"password"=>$password,
            "grade"=>$grade,"realname"=>$realname,"role"=>'teacher');
            $this->session->set_userdata($arr);
            $result=100;
        }else{
            $result=102;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));               
    }
        
    public function teacherAction(){  
        if($this->session->userdata('tid')){
            $tid=$this->session->userdata('tid');
            $this->load->model('teacher_model','teacher');
            $teacher=$this->teacher->findById($tid);
            $realname=$teacher->realname;
            $logintime=$teacher->logintime;
            $this->twig->render('teacher_index.html.twig',array('tid'=>$tid,
                'realname'=>$realname,'logintime'=>$logintime));     
        }else{
            redirect('/login');
        }
    }

    public function personalInfo($tid){
        $this->load->model('teacher_model','teacher');
        $info=$this->teacher->findById($tid);
        if($info==null){
            $result=102;
        }else{
            $result=100;   
        }
        $data['data']=$info;
        $data['errcode']=$result;
        print_r(json_encode($data));
    }

    public function updateInfo(){
        $realname = urldecode($this->input->post('realname'));
        $gender = urldecode($this->input->post('gender'));
        $teachernumber = $this->input->post('teachernumber');
        $grade=urldecode($this->input->post('grade'));
        $motto = urldecode($this->input->post('motto'));
        $tid=$this->session->userdata('tid');
        if($realname!=null){
        $this->load->model('teacher_model','teacher');
        $this->teacher->updateInfo($realname,$gender,$motto,$teachernumber,$tid,$grade);
        $result=100;
        }else{
        $result=102;
        } 
        $data['errcode']=$result;
        print_r(json_encode($data)); 
    }

    public function updatepassword(){
        $tid=$this->session->userdata('tid');
        $oldpassword=$this->input->post('oldPassword');
        $newpassword=$this->input->post('newPassword');
        $this->load->model('teacher_model','teacher');
        $result=$this->teacher->verifypassword($tid,$oldpassword);
        if($result==100){
            $this->teacher->updatepassword($tid,$newpassword);
        }
        $data['errcode']=$result;
        print_r(json_encode($data));
    }

    public function uploadAvatar(){  
        $this->load->model('teacher_model','teacher');
        $tid=$this->session->userdata('tid'); 
        if(!$sid ) print_r(json_encode(array('errcode'=>103, 'data'=>array()))); 
        $png2=$this->input->post('png2');

        $filepath120 = './upload_files/teacher/avatars/'.$tid.'_avatar_120.jpg';
        $avatar = '/upload_files/teacher/avatars/'.$tid.'_avatar_120.jpg';

        $somecontent2=base64_decode($png2);
        if ($handle=fopen($filepath120,'w+')) {
            if (FALSE==!fwrite($handle,$somecontent2)) {
                fclose($handle);
            }
        }
        $this->teacher->updateAvatar($tid,$avatar);
        print_r('success=done');//让前台弹出上传成功                   
    }

   /* public function uploadRes(){
        $config['upload_path']='./upload_files/teacher/resources/';
        $config['allowed_types']='avi|mp4|rmvb|gif|rar|jpg|mp3|rm';

        $this->load->library('upload');
        $this->upload->initialize($config);
        $field_name="upload_videos";
        if (!$this->upload->do_upload()){
            //$data = array('error' => $this->upload->display_errors());
            $result=102;
        }else{
            $info=$this->upload->data();
            $address='/upload_files/teacher/resources/'.$info['client_name'];
            $this->load->model('uploadres_model','uploadres');
            $name=urldecode($this->input->post('name'));
            $theme=urldecode($this->input->post('theme'));
            $custom_theme=urldecode($this->input->post('custom_theme'));
            $level=urldecode($this->input->post('level'));
            $description=urldecode($this->input->post('description'));
            $keyword=urldecode($this->input->post('keyword'));
            $keyphrase=urldecode($this->input->post('keyphrase'));
            $author=$this->session->userdata('realname');
            $author_group=$this->session->userdata('grade');
            $judge=$this->uploadres->insert($name,$theme,$custom_theme,$level,$description,$keyword,$keyphrase,date("Y-m-d   H:i:s"),$address,$author,$author_group);
            if($judge==true){
                $result=100;
            }else{
                $result=102;
            }
        }
        $data['errcode']=$result;
        print_r(json_encode($data));

    }*/

    public function findAllRes(){            
        $author_group=$this->session->userdata('grade');
        $this->load->model('uploadres_model','uploadres');
        $judge=$this->uploadres->search($author_group);
        $data['data']=$judge;
        print_r(json_encode($data));
    }

    public function recommendActivity($aid){
        $result=100;
        $sid=$this->input->post('sid');
        $t_name=$this->session->userdata('realname');
        $this->load->model('personal_activity_model','pa');
        $this->load->model('activity_model','activity');
        $this->load->helper('date');

        for($i=0;$i<count($sid);$i++){
            $info=$this->pa->find($sid[$i],$aid);
            $n=0;
            if($info==null){
                $this->pa->insert($sid[$i],$aid,$t_name,1,date("Y-m-d   H"),'');
                $n+=1;
            }else{
                $result=104;
            }           
        }
        if(count($sid)==1&&$this->activity->count()>1){
            $activity=$this->intelligentPush($sid[0]);
            if($activity!=''){
                for($j=0; $j <count($activity) ; $j++){
                    $info=$this->pa->find($sid[0],$activity[$j]['activity']->id);
                    if($info==null){ 
                        $this->pa->insert($sid[0],$activity[$j]['activity']->id,$t_name,2,date("Y-m-d   H")); 
                    }else{
                        $result=104;
                    }
                }    
            }
        }
        $count=$this->pa->studentCount($aid);
        $this->activity->update_studentcount($aid,$count);       
        if($result!=104||$n>0){
            $result=100;
        }
            
        $data['errcode']=$result;
        print_r(json_encode($data));

    }

    public function deleteActivity($aid){
        $this->load->model('personal_activity_model','pa');
        $this->load->model('activity_model','activity');
        $judge=$this->pa->t_delete($aid);
        $judge_a=$this->activity->delete($aid);
        if($judge==true && $judge_a==true){
            $result=100;
        }else{
            $result=102;
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

    public function activity($aid){
        $this->load->model('activity_model','activity');
        $info=$this->activity->findById($aid);
        $this->twig->render('activity.html.twig', array('aid' =>$info->id , 'title' =>$info->title ,
            'goal' =>$info->goal ,'type' =>$info->type ,'level' =>$info->level ,'theme' =>$info->theme ,
            'resource' =>$info->resource ,'author' =>$info->author ,'author_id' =>$info->author_id,
            'author_group' =>$info->author_group ,'student_count' =>$info->student_count,'content'=>$info->content,'role'=>'teacher'));
    }

    public function classAction(){
        $tid=$this->session->userdata('tid');
        $this->load->model("class_model","class");
        $data['members']=$this->class->findAllStudents($tid);
        $this->twig->render('teacher_class.html.twig',$data);
    }

    public function addStudents(){
        $tid=$this->session->userdata('tid');
        $realname=urldecode($this->input->post('realname'));
        $this->load->model('student_model','student');
        $res=$this->student->findByName($realname);
        if($res!=null){
            $sid=$res->id;
            $this->load->model('class_model','class');
            $result=$this->class->isStudents($tid,$sid);
        }else {
            $result=104;
        }       
        if($result==100){
            $this->class->insert($tid,$sid);
        }
        $data['errcode']=$result;
        print_r(json_encode($data));
    }

    public function deleteStudents(){
        $tid=$this->session->userdata('tid');
        $sid=$this->input->post('sid');
        $this->load->model("class_model","class");
        $judge=$this->class->isStudents($tid,$sid);
        if($judge==102){
            $this->class->deleteStudents($tid,$sid);
            $result=100;
        }else{
            $result=102;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));
    }

    public function showStudents(){
        $tid=$this->session->userdata('tid');
        $this->load->model('class_model','class');
        $info=$this->class->findAllStudents($tid);
        $data['data']=$info;
        print_r(json_encode($data));

    }

    public function intelligentPush($sid){
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
        return $activity;
    }
    public function t_deleteComment_s($commented_aid){
        $commented_sid=$this->input->post('commented_sid');
        $reviewer_tid=$this->session->userdata('tid');
        $this->load->model('comment_model','comment');
        $judge=$this->comment->t_delete_s($reviewer_tid,$commented_sid,$commented_aid);
        if($judge==true){
            $result=100;
        }else{
            $result=102;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));
    }
    public function t_deleteComment_t($commented_aid){
        $commented_tid=$this->input->post('commented_tid');
        $reviewer_tid=$this->session->userdata('tid');
        $this->load->model('comment_model','comment');
        $judge=$this->comment->t_delete_s($reviewer_tid,$commented_tid,$commented_aid);
        if($judge==true){
            $result=100;
        }else{
            $result=102;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));
    }        

   
}

/* End of file teacher.php */
/* Location: ./application/controllers/teacher.php */
?>
            