<?php

/**
* testresult_tb model
*/
class TestResult_Model  extends  CI_Model{

    var $sid='';
    var $first_result='';
    var $second_result='';
    var $first_style='';
    var $second_style='';
    var $hobby_result='';
    var $hobby_result_text='';
    /**
    *添加测试结果
    */
    public function insert($sid,$first_result,$second_result,$first_style,$second_style,$hobby_result,$hobby_result_text){
        $this->load->database();
        $sql="insert into  testresult_tb values(null,?,?,?,?,?,?,?)";
        $query=$this->db->query($sql,array($sid,$first_result,$first_style,$second_result,
        $second_style,$hobby_result,$hobby_result_text));
        $this->db->close();

    }
    /**
    *按学生id查询测试结果
    */
    public function findBySid($sid){
        $this->load->database();
        $sql="select * from testresult_tb where sid=?";
        $query=$this->db->query($sql,array($sid));
        if($query->num_rows()>0){
            $data=$query->row();
        }else{
            $data=null;
        }
        $this->db->close();
        return $data;
    }

}

?>