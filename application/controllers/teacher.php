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
            "role"=>'teacher');
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

}

/* End of file teacher.php */
/* Location: ./application/controllers/teacher.php */
?>
            