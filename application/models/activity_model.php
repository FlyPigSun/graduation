<?php

/**
* friends_tb model
*/
class Activity_Model  extends  CI_Model{
    var $id='';
    var $title='';
    var $content='';
    var $goal='';
    var $type='';
    var $level='';
    var $theme='';
    var $resource='';

    public function insert($title,$content,$goal,$type,$level,$theme,$resource){
        $this->load->database();
        $sql="insert into activity_tb value(null,?,?,?,?,?,?,?)";
        $query=$this->db->query($sql,array($title,$content,$goal,$type,$level,$theme,$resource));
        $this->db->close();
        return true;
    }

    public function delete($id){
        $this->load->database();
        $sql="delete from activity_tb where id=？"；
        $query=$this->db->query($sql,array($id));
        $this->db->close();
        return true;
    }

    public function findAll(){
        $this->load->database();
        $sql="select * from activity_tb ";
        $query=$this->db->query($sql);
        $this->db->close();
        return true;
    }


}?>