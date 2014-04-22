<?php

/**
* letter_tb model
*/
ini_set('date.timezone','Asia/Shanghai');
class Question_Model  extends  CI_Model{



    public function find($grade,$id){
        $this->load->database();
        $sql="select * from question_tb where grade=? and id =?";
        $query=$this->db->query($sql,array($grade,$id));
        $data=$query->row();          
        $this->db->close();
        return $data;
    }

}

?>