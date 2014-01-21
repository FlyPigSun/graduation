<?php
ini_set('date.timezone','Asia/Shanghai');
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment extends MY_Controller {
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
    public function addComment(){
        $comment_aid=$this->input->post('commented_aid');
        $author=urldecode($this->session->userdata('realname'));
        $role=$this->session->userdata('role');
        if($role=='student'){
            $reviewer_sid=$this->session->userdata('sid');
            $reviewer_tid=0;
        }else{
            $reviewer_sid=0;
            $reviewer_tid=$this->session->userdata('tid');
        }
        $comment=urldecode($this->input->post('comment'));
        $this->load->model('comment_model','comment');
        $judge=$this->comment->insert($comment_aid,$reviewer_sid,$reviewer_tid,$comment,date("Y-m-d  H:i:s"),$author);
        if($judge==true){
            $result=100;
        }else{
            $result=102;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));
    }

    public function showComment($commented_aid){
        $this->load->model('comment_model','comment');
        $role=$this->session->userdata('role');
        if($role=='student'){
            $reviewer_sid=$this->session->userdata('sid');
            $reviewer_tid=-1;
        }else{
            $reviewer_tid=$this->session->userdata('tid');
            $reviewer_sid=-1;
        }
        $info=$this->comment->findAll($commented_aid);
        $result=100;
        for($i=0;$i<count($info);$i++){
            $info[$i]=(array)$info[$i];
            if($info[$i]['reviewer_sid']==$reviewer_sid||$info[$i]['reviewer_tid']==$reviewer_tid){
                $delete = array('delete' =>1);
            }else{
                $delete = array('delete' =>0);
            }
            $info[$i]=array_merge($info[$i],$delete);
        }
        $data['errcode']=$result;
        $data['data']=$info;
        print_r(json_encode($data));
    }
    public function deleteComment($id){
        $this->load->model('comment_model','comment');
        $judge=$this->comment->delete($id);    
        if($judge==true){
            $result=100;
        }else{
            $result=102;
        }

        $data['errcode']=$result;
        print_r(json_encode($data));
    }
}

/* End of file comment.php */
/* Location: ./application/controllers/comment.php */
?>
            