<?php

/**
* friends_tb model
*/
class Friends_Model  extends  CI_Model{
    var $sid='';
    var $fid='';

    public function insert($sid,$fid){
        $this->load->database();
        $sql="insert into friends_tb value(null,?,?)";
        $query=$this->db->query($sql,array($sid,$fid));
        $this->db->close();
    }

    public function find($sid,$fid){
        $this->load->database();
        $sql="select * from friends_tb where sid=? and fid=?";
        $query=$this->db->query($sql,array($sid,$fid));
        if($query->num_rows()>0){
            $data=$query->row();
        }else {
            $data=null;
        }
        $this->db->close();
        return $data;
    }
    
    public function findAllFriends($sid){
        $this->load->database();
        $sql="select * from friends_tb f left join student_tb s on f.fid=s.id where f.sid=?";
        $query=$this->db->query($sql,array($sid));
        $result=$query->result();
        $data=array();
        foreach ($result as $row) {
           $data[]= array('id' =>$row->id ,'username'=>$row->username,"studentnumber"=>$row->studentnumber,
            "motto"=>$row->motto,"realname"=>$row->realname,"avatar"=>$row->avatar );
        }        
        $this->db->close();
        return $data;
        
    }

    public function deleteFriends($sid,$fid){
        $this->load->database();
        $sql="delete from friends_tb where sid=? and fid=?";
        $query=$this->db->query($sql,array($sid,$fid));
        $this->db->close();
    }

    public function isFriends($sid,$fid){
        $this->load->database();
        $sql="select * from friends_tb where sid=? and fid=?";
        $query=$this->db->query($sql,array($sid,$fid));
        if($query->num_rows()>0){
            $result=102;
        }else{
            $result=100;
        }
        return $result;
    }

}?>