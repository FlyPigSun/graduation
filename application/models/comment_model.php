<?php

/**
* comment_tb model
*/
class Comment_Model  extends  CI_Model{
    var $id='';
    var $commented_aid='';
    var $reviewer_sid='';
    var $reviewer_tid='';
    var $commented_sid='';
    var $commented_tid='';
    var $comment='';
    var $date='';

    public function insert($commented_aid,$reviewer_sid,$reviewer_tid,$commented_sid,$commented_tid,$comment,$date){
        $this->load->database();
        $sql="insert into comment_tb value(null,?,?,?,?,?,?,?)";
        $query=$this->db->query($sql,array($commented_aid,$reviewer_sid,$reviewer_tid,$commented_sid,$commented_tid,$comment,$date));
        $this->db->close();
        return true;
    }

    public function s_delete_s($reviewer_sid,$commented_sid,$commented_aid){
        $this->load->database();
        $sql="delete from comment_tb where reviewer_sid=? and commented_sid=? and commented_aid=?";
        $query=$this->db->query($sql,array($reviewer_sid,$commented_sid,$commented_aid));
        $this->db->close();
        return true;
    }

    public function t_delete_s($reviewer_tid,$commented_sid){
        $this->load->database();
        $sql="delete from comment_tb where reviewer_tid=? and commented_sid=? and commented_aid=?";
        $query=$this->db->query($sql,array($reviewer_tid,$commented_sid,$commented_aid));
        $this->db->close();
        return true;
    }

    public function s_delete_t($reviewer_sid,$commented_tid,$commented_aid){
        $this->load->database();
        $sql="delete from comment_tb where reviewer_sid=? and commented_tid=? and commented_aid=?";
        $query=$this->db->query($sql,array($reviewer_sid,$commented_tid,$commented_aid));
        $this->db->close();
        return true;
    }

    public function t_delete_s($reviewer_tid,$commented_sid,$commented_aid){
        $this->load->database();
        $sql="delete from comment_tb where reviewer_tid=? and commented_sid=? and commented_aid=?";
        $query=$this->db->query($sql,array($reviewer_tid,$commented_sid,$commented_aid));
        $this->db->close();
        return true;
    }

    public function findAll($commented_aid){
        $this->load->database();
        $sql="select * from comment_tb where commented_aid=? order by date desc";
        $query=$this->db->query($sql,array($commented_aid));
        $data=$query->result();
        $this->db->close();
        return $data;
    }

    public function count(){
       $this->load->database();
       $sql="select count(*) as a from comment_tb";
       $query=$this->db->query($sql) ;
       if($query->num_rows()>0){
            $data=$query->row();
        }else {
            $data=null;
        }
        $this->db->close();
        return $data->a;
    }


}?>