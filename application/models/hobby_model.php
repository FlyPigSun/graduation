<?php

/**
* hobby_tb model
*/
class Hobby_Model  extends  CI_Model{
    var $id='';
    var $xingqu='';
    var $show='';

    public function insert($xingqu){
        $this->load->database();
        $sql="insert into hobby_tb value(null,?,0) ";
        $query=$this->db->query($sql,array($xingqu));
        $this->db->close();
    }

    public function  find($xingqu){
        $this->load->database();
        $sql="select * from hobby_tb where xingqu=?";
        $query=$this->db->query($sql,array($xingqu));
        if($query->num_rows()>0){
            $data["obj"]=$query->row();
        }else {
            $data["obj"]=null;
        }
        $this->db->close();
        return $data;
    }




}?>