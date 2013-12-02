<?php

/**
* letter_tb model
*/
ini_set('date.timezone','Asia/Shanghai');
class Student_Model  extends  CI_Model{

    var $from_id='';
    var $to_id='';
    var $content='';
    var $from_role='';
    var $to_role='';



    //增加站内信
    public function insert($from_id,$to_id,$content,$from_role,$to_role){
        $this->load->database();
        $sql="insert into letter_tb varlue(null,?,?,?,?,?)";
        $query=$this->db->query($sql,array($from_id,$to_id,$content,$from_role,$to_role));
        $this->db->close();
    }
    //查找站内信
    public function find($to_id,$to_role){
        $this->load->database();
        $sql="select * from letter_tb where $to_sid=? and $to_role=?";
        $query=$this->db->query($sql,array($to_id,$to_role));
        $result=$query->result();
        $data=array();
        foreach ($result as $row) {
           $data[]=$row->content;
        }        
        $this->db->close();
        return $data;
    }
    
    //删除站内信
    public function deleteLetter($to_id,$to_role){
        $this->load->database();
        $sql="delete from letter_tb where to_sid=? and to_role=?";
        $query=$this->db->query($sql,array($to_id,$to_role));
        $this->db->close();
    }

}

?>