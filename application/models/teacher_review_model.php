<?php

/**
* student_tb model
*/
ini_set('date.timezone','Asia/Shanghai');
class Teacher_review_Model  extends  CI_Model{

    var $id='';
    var $tid='';
    var $aid='';
    var $theme_match_degree='';
    var $res_match_degree='';
    var $audio_clear_degree='';
    var $voice_fluent_degree='';
    var $class_effect_degree='';
    var $is_do='';
    
    public function insert($tid,$aid,$theme_match_degree,$res_match_degree,$audio_clear_degree,$voice_fluent_degree,
        $class_effect_degree,$is_do){
        $this->load->database();
        $sql="insert into teacher_review_tb value(null,?,?,?,?,?,?,?,?)";
        $query=$this->db->query($sql,array($tid,$aid,$theme_match_degree,$res_match_degree,$audio_clear_degree,
                $voice_fluent_degree,
                $class_effect_degree,$is_do));
        $this->db->close();
        return true;

    }

    public function findByAid($aid){
        $this->load->database();
        $sql="select * from teacher_review_tb  where aid=?";
        $query=$this->db->query($sql,array($aid));
        $data=$query->row();
        $this->db->close();
        return $data;
    }

    public function findBySid($sid){
        $this->load->database();
        $sql="select * from teacher_review_tb  where sid=?";
        $query=$this->db->query($sql,array($sid));
        $data=$query->row();
        $this->db->close();
        return $data;
    }

    public function find($aid,$tid){
        $this->load->database();
        $sql="select * from teacher_review_tb  where tid=? and aid=?";
        $query=$this->db->query($sql,array($tid,$aid));
        if($query->num_rows()>0){
            $data=$query->row();
        }else {
            $data=null;
        }
        $this->db->close();
        return $data;
    }

    public function delete($id){
        $this->load->database();
        $sql="delete from teacher_review_tb where id=?";
        $query=$this->db->query($sql,array($id));
        $this->db->close();
        return true;
    }






}

?>