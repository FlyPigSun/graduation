<?php

/**
* letter_tb model
*/
ini_set('date.timezone','Asia/Shanghai');
class Grammar_Model  extends  CI_Model{

    public function find($keyword){
        $this->load->database();
        $sql="select * from grammarfind_tb where grammar_keyword=?";
        $query=$this->db->query($sql,array($keyword));
        $data=$query->row();          
        $this->db->close();
        return $data;
    }

}

?>

