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
        $reviewer_sid=$this->input->post('reviewer_sid');
        $reviewer_tid=$this->input->post('reviewer_tid');
        $commented_sid=$this->input->post('commented_sid');
        $commented_tid=$this->input->post('commented_tid');
        $comment=$this->input->post('comment');
        $this->load->model('comment_model','comment');
        $judge=$this->comment->insert($comment_aid,$reviewer_sid,$reviewer_tid,$commented_sid,$commented_tid,$comment,date("Y-m-d  H:i:s"));
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
        $info=$this->comment->findAll($commented_aid);
        $data['data']=$info;
        print_r(json_encode($data));
    }
}

/* End of file comment.php */
/* Location: ./application/controllers/comment.php */
?>
            