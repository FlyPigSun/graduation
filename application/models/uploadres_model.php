<?php

/**
* Resources_tb model
*/
class UploadRes_Model  extends  CI_Model{
    var $name='';
    var $theme='';
    var $custom_theme='';
    var $level='';
    var $description='';
    var $keyword='';
    var $keyphrase='';
    var $upload_time='';
    var $address='';
    var $author='';
    var $author_group='';

    public function insert($name,$theme,$custom_theme,$level,$description,$keyword,$keyphrase,$upload_time,$address,$author,$author_group,$file_size){
        $this->load->database();
        $sql="insert into resources_tb value(null,?,?,?,?,?,?,?,?,?,?,?,?)";
        $query=$this->db->query($sql,array($name,$theme,$custom_theme,$level,$description,$keyword,$keyphrase,$upload_time,$address,$author,$author_group,$file_size));
        $this->db->close();
        return true;
    }

    public function search($author_group){
        $this->load->database();
        $sql="select * from resources_tb where author_group=?";
        $query=$this->db->query($sql,array($author_group));
        $data=$query->result();
        return $data;
    }

}
?>