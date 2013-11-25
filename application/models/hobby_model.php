<?php

/**
* hobby_tb model
*/
class Hobby_Model  extends  CI_Model{
    var $id='';
    var $hobby='';
    var $show='';

    public function insert($hobby){
        $this->load->database();
        $sql="insert into hobby_tb value(null,?,0) ";
        $query=$this->db->query($sql,array($hobby));
        $this->db->close();
    }

    public function  find($hobby){
        $this->load->database();
        $sql="select * from hobby_tb where hobby=?";
        $query=$this->db->query($sql,array($hobby));
        if($query->num_rows()>0){
            $data["obj"]=$query->row();
        }else {
            $data["obj"]=null;
        }
        $this->db->close();
        return $data;
    }




}?>