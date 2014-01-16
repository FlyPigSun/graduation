<?php

/**
* student_tb model
*/
ini_set('date.timezone','Asia/Shanghai');
class Personal_Activity_Model  extends  CI_Model{

    var $id='';
    var $sid='';
    var $aid='';
    var $s_answer='';
    var $s_annex='';
    var $t_assess='';
    var $t_name='';
    var $is_finish='';
    
    public function insert($sid,$aid,$t_name,$is_push){
        $this->load->database();
        $sql="insert into personal_activity_tb value(null,?,?,null,null,null,?,0,?)";
        $query=$this->db->query($sql,array($sid,$aid,$t_name,$is_push));
        $this->db->close();
        return true;

    }
    public function find($sid,$aid){
        $this->load->database();
        $sql="select * from personal_activity_tb where sid=? and aid=?";
        $query=$this->db->query($sql,array($sid,$aid));
        if($query->num_rows()>0){
            $data=$query->row();
        }else {
            $data=null;
        }
        $this->db->close();
        return $data;
    }

    public function findBySid($sid){
        $this->load->database();
        $sql="select s.realname,s.studentnumber,s.gender,a.title from student_tb s
              inner join personal_activity_tb pa on s.id=pa.sid
              inner join activity_tb a on pa.aid=a.id where pa.sid=?";
        $query=$this->db->query($sql,array($sid));
        $data=$query->result();
        $this->db->close();
        return $data;
    }

    public function findByAid($aid){
        $this->load->database();
        $sql="select s.realname,s.studentnumber,s.gender,a.title from student_tb s
              inner join personal_activity_tb pa on s.id=pa.sid
              inner join activity_tb a on pa.aid=a.id where pa.aid=?";
        $query=$this->db->query($sql,array($aid));
        $data=$query->result();
        $this->db->close();
        return $data;
    }
    //学生提交答案
    public function s_update($sid,$s_answer,$s_annex){
        $this->load->database();
        $sql="update personal_activity_tb set s_answer=?,s_annex=?,is_finish=1 where sid=?";
        $query=$this->db->query($sql,array($s_answer,$s_annex,$sid));
        $this->db->close();
        return true;
    }
    //教师评价
    public function t_update($t_assess,$sid){
        $this->load->database();
        $sql="update personal_activity_tb set t_assess=? where sid=?";
        $query=$this->db->query($sql,array($t_assess,$sid));
        $this->db->close();
        return true;

    }
    //学生退出活动 
    public function s_delete($sid,$aid){
        $this->load->database();
        $sql="delete from personal_activity_tb where sid=? and aid=?";
        $query=$this->db->query($sql,array($sid,$aid));
        $this->db->close();
        return true;
    }
    //教师删除活动
    public function t_delete($aid){
        $this->load->database();
        $sql="delete from personal_activity_tb where aid=?";
        $query=$this->db->query($sql,array($aid));
        $this->db->close();
        return true;
    }
    //参与活动的人数
    public function studentCount($aid){
       $this->load->database();
       $sql="select count(aid) as a from personal_activity_tb where aid=?";
       $query=$this->db->query($sql,array($aid)) ;
       if($query->num_rows()>0){
            $data=$query->row();
        }else {
            $data=null;
        }
        $this->db->close();
        return $data->a;
       
    }
    //找到所有教师推荐活动的id
    public function find_aid_teacherPush($sid){
        $this->load->database();
        $sql="select * from personal_activity_tb where sid=? and is_push=1";
        $query=$this->db->query($sql,array($sid));
        $data=$query->result();
        $this->db->close();
        return $data;
    }

    




}

?>