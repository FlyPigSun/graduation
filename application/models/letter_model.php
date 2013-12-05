<?php

/**
* letter_tb model
*/
ini_set('date.timezone','Asia/Shanghai');
class Letter_Model  extends  CI_Model{

    var $from_id='';
    var $to_id='';
    var $content='';
    var $title='';
    



    //增加站内信
    public function insert($from_id,$to_id,$title,$content){
        $this->load->database();
        $sql="insert into letter_tb value(null,?,?,?,?,0)";
        $query=$this->db->query($sql,array($from_id,$to_id,$title,$content));
        $this->db->close();
        return true;
    }
    //发件箱
    public function findTo($sid){
        $this->load->database();
        $sql="select s.realname,l.title,l.content from letter_tb l left join student_tb s on l.from_id=s.id where l.from_id=? and is_delete=0";
        $query=$this->db->query($sql,array($sid));
        $result=$query->result();
        $data=array();
        foreach ($result as $row) {
           $data[]=array("toRealname"=>$row->realname,"title"=>$row->title,"content"=>$row->content);
        }        
        $this->db->close();
        return $data;
    }
    //收件箱
    public function findFrom($sid){
        $this->load->database();
        $sql="select s.realname,l.title,l.content from letter_tb l left join student_tb s on l.to_id=s.id where l.to_id=? and is_delete=0";
         $query=$this->db->query($sql,array($sid));
        $result=$query->result();
        $data=array();
        foreach ($result as $row) {
           $data[]=array("toRealname"=>$row->realname,"title"=>$row->title,"content"=>$row->content);
        }        
        $this->db->close();
        return $data;
    }
    
    //删除站内信
    public function deleteLetter($id){
        $this->load->database();
        $sql="update letter_tb set is_delete=1 where id=?";
        $query=$this->db->query($sql,array($id));
        $this->db->close();
        return true;
    }

}

?>