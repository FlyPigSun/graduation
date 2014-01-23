<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Question extends MY_Controller {

    public function showQuestion(){

        $grade=$this->session->userdata('grade');
        $this->load->model('question_model','question');
        $arr=array();
        if($grade=='一年级'){
            while(count($arr)<5){
                $a=rand(1,10);//产生随机数
                if(!in_array($a,$arr)){ //判断$arr中是否有$a,有则返回true,否则false
                    $arr[]=$a; //把$a 赋值给数组元素
                }
            }
        }else if($grade=='二年级'){
            while(count($arr)<5){
                $a=rand(11,20);//产生随机数
                if(!in_array($a,$arr)){ //判断$arr中是否有$a,有则返回true,否则false
                    $arr[]=$a; //把$a 赋值给数组元素
                }
            }
        }
        for($i=0;$i<5;$i++){            
            $info[$i]=$this->question->find($grade,$arr[$i]);
            $info[$i]=array_merge((array)$info[$i],array('number'=>$i+1));
        }
        $result=100;
        $data['errcode']=$result;
        $data['data']=$info;
        print_r(json_encode($data));
    }

    public function result(){
        $q_result=$this->input->post('result');
        $sid=$this->session->userdata('sid');
        $this->load->model('student_model','student');
        $judge=$this->student->upadeQuestionResult($sid,$q_result);
        if($judge==true){
            $result=100;
        }else{
            $result=102;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));

    }

}
?>