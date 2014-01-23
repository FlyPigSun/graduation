<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student extends MY_Controller {
  
    public function studentAction(){  
        if($this->session->userdata('sid')){
            $sid=$this->session->userdata('sid');
            $this->load->model('student_model','student');
            $student=$this->student->findById($sid);
            $notfirst=$student->notfirst;
            $realname=$student->realname;
            $logintime=$student->logintime;
            $mood = $student->mood;
            $this->twig->render('student_index.html.twig',array('mood'=>$mood,'notfirst'=>$notfirst,'sid'=>$sid,
                'realname'=>$realname,'logintime'=>$logintime));
        }else{
            redirect('/login');
        }
    }

    public function register(){
        $username=$this->input->post('username');
        $password=$this->input->post('password');
        $realname=urldecode($this->input->post('realname'));
        $studentnumber=$this->input->post('studentnum');
        $grade=urldecode($this->input->post('grade'));
        $class=$this->input->post('class');
        $gender=urldecode($this->input->post('gender'));
        $this->load->model('student_model','student');
        $judge=$this->student->find($username);
        $avatar="/upload_files/student/avatars/default_avatar.jpg";
        if($judge==null){
            $this->student->insert($username,$password,$realname,$studentnumber,$grade,$class,$gender,$avatar);
            $mood=null;
            $this->student->login($username,$password,$mood,date("Y-m-d   H:i:s"));
            $arr=array("sid"=>$this->student->id,"username"=>$username,
            "time"=>$this->student->logintime,"password"=>$password,
            "teachernumber"=>$studentnumber,"grade"=>$grade,
            "class"=>$class,"role"=>'student',"character"=>'',"testscore"=>0,"notfirst"=>0,"realname"=>$realname);
            $this->session->set_userdata($arr);
            $result=100;
        }else{
            $result=102;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));
    }
    
    public function testSubmit(){
        $sid=$this->input->post('sid');
        $first_result=$this->input->post('first_result');
        $second_result=$this->input->post('second_result');
        $first_style=urldecode($this->input->post('first_style'));
        $second_style=urldecode($this->input->post('second_style'));
        $hobby_result=$this->input->post('hobby_result');
        $hobby_result_text=urldecode($this->input->post('hobby_result_text'));
        $this->load->model('testresult_model','testresult');
        $judge=$this->testresult->findBySid($sid);
        if($judge==null){
            $this->testresult->insert($sid,$first_result,$second_result,$first_style,
            $second_style,$hobby_result,$hobby_result_text);
            $this->load->model('student_model','student');
            $this->student->doTest($sid);
            $result=100;
        }else{
            
            $result=102;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));
    }

    public function personalInfo($sid){
        $this->load->model('student_model','student');
        $info=$this->student->findById($sid);
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
        $studentnumber = $this->input->post('studentnumber');
        $grade = urldecode($this->input->post('grade'));
        $motto = urldecode($this->input->post('motto'));
        $class='';
        $sid=$this->session->userdata('sid');
        if($realname!=null){
        $this->load->model('student_model','student');
        $this->student->updateInfo($realname,$gender,$grade,$class,$motto,$studentnumber,$sid);
        $result=100;
        }else{
        $result=102;
        } 
        $data['errcode']=$result;
        print_r(json_encode($data)); 
    }

    public function updatepassword(){
        $sid=$this->session->userdata('sid');
        $oldpassword=$this->input->post('oldPassword');
        $newpassword=$this->input->post('newPassword');
        $this->load->model('student_model','student');
        $result=$this->student->verifypassword($sid,$oldpassword);
        //print_r($result);
        if($result==100){
            $this->student->updatepassword($sid,$newpassword);
        }
        $data['errcode']=$result;
        print_r(json_encode($data));
    }

    public function myStyle(){
        $sid=$this->session->userdata('sid');
        $this->load->model('testresult_model','testresult');
        $testresult=$this->testresult->findBySid($sid);
        $data['data']=$testresult;
        $result=100;
        $data['errcode']=$result;
        print_r(json_encode($data));
    }

    public function selectHobby(){
        $sid=$this->session->userdata('sid');
        $hobby=urldecode($this->input->post('hobby'));
        $this->load->model('hobby_model','hobby');
        $judge=$this->hobby->find($sid,$hobby);
        $judgecount=sizeof($this->hobby->findAllHobby($sid));
        if($judgecount<=19&&$judge==null){
            $this->hobby->insert($sid,$hobby);
            $result=100;
        }else if($judgecount>19){
            $result=104;
        }else{
            $result=102;
        }
        $data['errcode']=$result;
        $data['num']=$judgecount;
        print_r(json_encode($data));
    }

    public function myHobby(){
        $hobby=urldecode($this->input->post('myhobby'));
        $sid=$this->session->userdata('sid');
        $this->load->model('hobby_model','hobby');
        $judge=$this->hobby->find($sid,$hobby);
        $judgecount=sizeof($this->hobby->findAllHobby($sid));
        if($judgecount<=19&&$judge==null){
            $this->hobby->insert($sid,$hobby);
            $result=100;
        }else if($judgecount>19){
            $result=104;
        }else{
            $result=102;
        }
        $data['errcode']=$result;
        $data['num']=$judgecount;
        print_r(json_encode($data));
    }

    public function findAllHobby(){
        $sid=$this->session->userdata('sid');
        $this->load->model('hobby_model','hobby');
        $info=$this->hobby->findAllHobby($sid);
        $data['data']=$info;
        print_r(json_encode($data));
    }

    public function deleteHobby(){
        $sid=$this->session->userdata('sid');
        $hobby=$this->input->post('hobby');
        $this->load->model('hobby_model','hobby');
        $judge=$this->hobby->find($sid,$hobby);
        if($judge!=null){
            $info=$this->hobby->deleteHobby($sid,$hobby);
            $result=100;
        }else{
            $result=102;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));
    }

    public function uploadAvatar(){  
        $this->load->model('student_model','student');
        $sid=$this->session->userdata('sid'); 
        if(!$sid ) print_r(json_encode(array('errcode'=>103, 'data'=>array()))); 
        $png2=$this->input->post('png2');

        $filepath120 = './upload_files/student/avatars/'.$sid.'_avatar_120.jpg';
        $avatar = '/upload_files/student/avatars/'.$sid.'_avatar_120.jpg';

        $somecontent2=base64_decode($png2);
        if ($handle=fopen($filepath120,'w+')) {
            if (FALSE==!fwrite($handle,$somecontent2)) {
                fclose($handle);
            }
        }
        $this->student->updateAvatar($sid,$avatar);
        print_r('success=done');//让前台弹出上传成功                   
    }

    public function getAvatarAddress(){
        $sid=$this->session->userdata('sid');
        $this->load->model('student_model','student');
        $obj=$this->student->findById($sid);
        $data=$obj->avatar;
        return $data;
    }
    //我的印象
    public function myQuestionnaireResult(){
        $sid=$this->session->userdata('sid');
        $this->load->model('testresult_model','testresult');
        $judge=$this->testresult->findBySid($sid);
        if($judge->first_style=='活跃型'){
            $first_content="活跃型学习者倾向于通过积极地做一些事—讨论或应用或解释给别人听来掌握信息。“来，我们试试看，看会怎样”这是活跃型学习者的口头禅。
                            活跃型学习者比倾向于独立工作的沉思型学习者更喜欢集体工作。";
            if($judge->second_style=='言语型'){
                $second_content=" 视觉型学习者很擅长记住他们所看到的东西，如图片、图表、流程图、图像、影片和演示中的内容";
            }else{
                $second_content="言语型学习者更擅长从文字的和口头的解释中获取信息。";
            }
        }else{
            $first_content="沉思型学习者更喜欢首先安静地思考问题。“我们先好好想想吧”是沉思型学习者的通常反应。";
            if($judge->second_style=='言语型'){
                $second_content=" 视觉型学习者很擅长记住他们所看到的东西，如图片、图表、流程图、图像、影片和演示中的内容";
            }else{
                $second_content="言语型学习者更擅长从文字的和口头的解释中获取信息。";
            }
        }       
        if($judge==null){
            $result=102;
        }else{
            $result=100;
        }
        $data['errcode']=$result;
        $data['data']=$judge;
        $data['first_content']=$first_content;
        $data['second_content']=$second_content;
        print_r(json_encode($data));

    }

    public function getMood(){
        $sid=$this->session->userdata('sid');
        $this->load->model('student_model','student');
        $judge=$this->student->findById($sid);
        if($judge==null){
            $result=102;
        }else{
            $result=100;
            $info=$judge->mood;
        }
        $data['errcode']=$result;
        $data['data']=$info;
        print_r(json_encode($data));
    }

    public function friendsAction(){
        $sid=$this->session->userdata('sid');
        $this->load->model("friends_model","friends");
        $data['members']=$this->friends->findAllFriends($sid);
        $this->twig->render('student_friends.html.twig',$data);
    }

    public function addFriends(){
        $sid=$this->session->userdata('sid');
        $realname=urldecode($this->input->post('realname'));
        $this->load->model('student_model','student');
        $res=$this->student->findByName($realname);
        if($res!=null){
            $fid=$res->id;
            $this->load->model('friends_model','friends');
            $result=$this->friends->isFriends($sid,$fid);
        }else {
            $result=104;
        }       
        if($result==100){
            $this->friends->insert($sid,$fid);
            $this->friends->insert($fid,$sid);
        }
        $data['errcode']=$result;
        print_r(json_encode($data));
    }

    public function deleteFriends(){
        $sid=$this->session->userdata('sid');
        $fid=$this->input->post('fid');
        $this->load->model("friends_model","friends");
        $judge=$this->friends->isFriends($sid,$fid);
        if($judge==102){
            $this->friends->deleteFriends($sid,$fid);
            $this->friends->deleteFriends($fid,$sid);
            $result=100;
        }else{
            $result=102;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));
    }

    public function sendLetters(){
        $from_id=$this->session->userdata('sid');
        $to_id=$this->input->post('to_id');
        $title=urldecode($this->input->post('title'));
        $content=urldecode($this->input->post('content'));
        $this->load->model('letter_model','letter');
        $judge=$this->letter->insert($from_id,$to_id,$title,$content,date("Y-m-d   H:i:s"));
        if($judge==true){
            $result=100;
        }else{
            $result=102;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));

    }




    public function inBox(){
        $sid=$this->session->userdata('sid');
        $this->load->model('letter_model','letter');
        $judge=$this->letter->findFrom($sid);
        $result=100;                   
        $data['data']=$judge;
        $data['errcode']=$result;
        print_r(json_encode($data));
    }

    public function outBox(){
        $sid=$this->session->userdata('sid');
        $this->load->model('letter_model','letter');
        $judge=$this->letter->findTo($sid);
        $result=100;
        $data['data']=$judge;
        $data['errcode']=$result;
        print_r(json_encode($data));
    }

    public function deleteLetter(){
        $id=$this->input->post('id');
        $this->load->model('letter_model','letter');
        $judge=$this->letter->deleteLetter($id);
        if($judge==true){
            $result=100;
        }else{
            $result=102;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));
    }

    public function myActivity(){
        $sid=$this->session->userdata('sid');
        $this->load->model('personal_activity_model','pa');
        $info=$this->pa->findBySid($sid);
        $isDo=array('isDo'=>0);
        for($i=0;$i<count($info);$i++){
            $info[$i]=array_merge((array)$info[$i],$isDo);
        }
        $data['data']=$info;
        print_r(json_encode($data));
    }

    public function deleteActivity(){
        $sid=$this->session->userdata('sid');
        $aid=$this->input->post('aid');
        $this->load->model('personal_activity_model','pa');
        $judge=$this->pa->s_delete($sid,$aid);
        if($judge==true){
            $result=100;
        }else{
            $result=102;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));
    }

    public function addActivity(){
        $sid=$this->session->userdata('sid');
        $aid=$this->input->post('aid');
        $this->load->model('activity_model','activity');
        $t_name=$this->activity->findById($aid)->author;
        $this->load->model('personal_activity_model','pa');
        $judge=$this->pa->insert($sid,$aid,$t_name);
        if($judge==true){
            $this->load->model('personal_activity_model','pa');
            $count=$this->pa->studentCount($aid);
            $this->activity->update_studentcount($aid,$count);
            $result=100;
        }else{
            $result=102;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));
    }

    public function systemPush(){
        $sid=$this->session->userdata('sid');
        $this->load->model('personal_activity_model','pa');
        $info=$this->pa->findSystemPush($sid);
        $result=100;
        $data['errcode']=$result;
        $data['data']=$info;
        print_r(json_encode($data));
    }

     

  

}
?>