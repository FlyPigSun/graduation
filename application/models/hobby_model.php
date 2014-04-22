<?php

/**
* hobby_tb model
*/
class Hobby_Model  extends  CI_Model{
    var $id='';
    var $hobby='';

    public function insert($sid,$hobby){
        $this->load->database();
        $sql="insert into hobby_tb value(null,?,?)";
        $query=$this->db->query($sql,array($sid,$hobby));
        $this->db->close();
    }

    public function find($sid,$hobby){
        $this->load->database();
        $sql="select * from hobby_tb where hobby=? and sid=?";
        $query=$this->db->query($sql,array($hobby,$sid));
        if($query->num_rows()>0){
            $data=$query->row();
        }else {
            $data=null;
        }
        $this->db->close();
        return $data;
    }
    
    public function findAllHobby($sid){
        $this->load->database();
        $sql="select * from hobby_tb where sid=?";
        $query=$this->db->query($sql,array($sid));
        $result=$query->result();
        $data=array();
        foreach ($result as $row) {
           $data[]=$row->hobby;
        }        
        $this->db->close();
        return $data;
        
    }

    public function deleteHobby($sid,$hobby){
        $this->load->database();
        $sql="delete from hobby_tb where sid=? and hobby=?";
        $query=$this->db->query($sql,array($sid,$hobby));
        $this->db->close();
    }

}?>