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
            "grade"=>$grade,"realname"=>$realname);
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
        $studentCount=$this->studentCount();
        $data['data']=$judge.$studentCount;
        print_r(json_encode($data));
    }

    public function recommendActivity(){
        $sid=array($this->input->post('sid'));
        $aid=$this->input->post('aid');
        $t_name=$this->session->userdata('realname');
        $this->load->model('personal_activity_model','pa');
        for($i=0;$i<count($sid);$i++){
            $info=$this->pa->find($sid[$i],$aid);
            if($info==null){
                $this->pa->insert($sid[$i],$aid,$t_name);
            }            
        }
        if($i==count($sid)){
            $result=100;
        }else{
            $result=102;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));

    }

    public function deleteActivity(){
        $aid=$this->input->post('aid');
        $this->load->model('personal_activity_model','pa');
        $judge=$this->pa->t_delete($aid);
        if($judge==true){
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

    public function Activityaction($aid){
        $this->load->model('activity_model','activity');
        $info=$this->activity->findById($aid);
        $this->twig->render('activity.html.twig',$info);

    }
   
}

/* End of file teacher.php */
/* Location: ./application/controllers/teacher.php */
?>
            