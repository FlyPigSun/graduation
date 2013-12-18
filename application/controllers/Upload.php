<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Upload extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url','file'));
    }

    public function index() {
        $this->load->view('admin/upload', array('error' => ''));
    }

    public function do_upload() {
        $upload_path_url = base_url() . 'upload_files/activity/';
        $config['upload_path'] = FCPATH . 'upload_files/activity/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif|mp3|doc|docx|txt|pdf|word|wps';
        $this->load->library('upload', $config);
        //$field_name = 'userfile';
        $_FILES['userfile']['name']=iconv("utf-8","gbk",$_FILES['userfile']['name']); 
        if (!$this->upload->do_upload()) {
            $error = array('error' => $this->upload->display_errors());
            print_r($error);
        } else {           
            $data = $this->upload->data();
            //if($data['file_ext']=='.txt'||$data['file_ext']=='.doc'){
      /*        function MakePropertyValue($name, $value,$osm){ 
                   $oStruct = $osm->Bridge_GetStruct("com.sun.star.beans.PropertyValue"); 
                   $oStruct->Name = $name; 
                   $oStruct->Value = $value; 
                   return $oStruct; 
                } 
                function odt2pdf($doc_url, $output_url){
                $osm = new COM("com.sun.star.ServiceManager") or die ("Please be sure that OpenOffice.org is installed.\n");
                $args = array(MakePropertyValue("Hidden",true,$osm));
                $oDesktop = $osm->createInstance("com.sun.star.frame.Desktop");
                print_r($doc_url);
                $oWriterDoc = $oDesktop->loadComponentFromURL($doc_url,"_blank", 0, $args);
                $aFilterData  = array();
                $aFilterData [0] = $osm->Bridge_GetStruct("com.sun.star.beans.PropertyValue");
                $aFilterData [0]->Name = "SelectPdfVersion";
                $aFilterData [0]->Value = 1;
                $obj  = $osm->Bridge_GetValueObject();
                $obj->set("[]com.sun.star.beans.PropertyValue",$aFilterData );
                $storePDF = array();
                $storePDF[0] = $osm->Bridge_GetStruct("com.sun.star.beans.PropertyValue");
                $storePDF[0]->Name = "FilterName";
                $storePDF[0
                ]->Value = "writer_pdf_Export";
                $storePDF[1] = $osm->Bridge_GetStruct("com.sun.star.beans.PropertyValue");   
                $storePDF[1]->Name = "FilterData";
                $storePDF[1]->Value = $obj;
                print_r($output_url);
                print_r($storePDF);
                $oWriterDoc->storeToURL($output_url,$storePDF);
                $oWriterDoc->close(true);
                }
                $output_dir = FCPATH .'upload_files/activity/';
                $doc_file = FCPATH .'upload_files/activity/11.doc';
                $pdf_file = "output2.pdf";
                $output_file = $output_dir . $pdf_file;
                $doc_file = "file:///" . $doc_file;
                $output_file = "file:///" . $output_file;
                odt2pdf($doc_file,$output_file);*/
  

    
                set_time_limit(0);  
                function MakePropertyValue($name,$value,$osm){  
                $oStruct = $osm->Bridge_GetStruct
                ("com.sun.star.beans.PropertyValue");  
                $oStruct->Name = $name;  
                $oStruct->Value = $value;  
                return $oStruct;  
                }  
                function word2pdf($doc_url, $output_url){  
                $osm = new COM("com.sun.star.ServiceManager") 
                or die ("Please be sure that OpenOffice.org 用openoffice 将doc转换成pdf 的配置
                is installed.n");
                var_dump($osm)  ;
                $args = MakePropertyValue("Hidden",true,$osm); 
                print_r($args->Value) ;

                $oDesktop = $osm->createInstance("com.sun.star
                .frame.Desktop");
                var_dump($oDesktop);
                $oWriterDoc = $oDesktop->loadComponentFromURL($doc_url,"_blank",0,$args);  
                $export_args = array(MakePropertyValue
                ("FilterName","writer_pdf_Export",$osm));  
                $oWriterDoc->storeToURL($output_url,$export_args);  
                $oWriterDoc->close(true);  
                }  
                $output_dir = FCPATH ."upload_files/activity/";
                //$doc_file = "D:\graduation\upload_files\activity/2111.doc";
                $doc_file = FCPATH ."upload_files/activity/".$_FILES['userfile']['name'];
                $pdf_file = "2111.pdf";
                $output_file = $output_dir . $pdf_file;
                $doc_file = "file:///" . $doc_file;
                $output_file = "file:///" . $output_file;
                word2pdf($doc_file,$output_file);
                //$oDesktop->terminate();//关闭
                $osm->dispose();//关闭
            //}


            $config = array();
            $config['image_library'] = 'gd2';
            $config['source_image'] = $data['full_path'];
            $config['create_thumb'] = TRUE;
            $config['new_image'] = $data['file_path'] . 'thumbs/';
            $config['maintain_ratio'] = TRUE;
            $config['thumb_marker'] = '';
            $config['width'] = 75;
            $config['height'] = 50;
            $this->load->library('image_lib', $config);
            $this->image_lib->resize();

            $address='/upload_files/activity/'.iconv("gbk","utf-8",$data['file_name']);
            $this->load->model('uploadres_model','uploadres');
            $name=iconv("gbk","utf-8",$data['file_name']);
            $theme=urldecode($this->input->post('theme'));
            $custom_theme=urldecode($this->input->post('custom_theme'));
            $level=urldecode($this->input->post('level'));
            $description=urldecode($this->input->post('description'));
            $keyword=urldecode($this->input->post('keyword'));
            $keyphrase=urldecode($this->input->post('keyphrase'));
            $author=$this->session->userdata('realname');
            $author_group=$this->session->userdata('grade');
            $file_size=$data['file_size'];
            $res_ext=$data['file_ext'];
            print_r($res_ext);
            if($res_ext=='.jpg'||$res_ext=='.jpeg'||$res_ext=='.png'|$res_ext=='.gif'){
                $res_type='img';
            }else if($res_ext=='.doc'||$res_ext=='.docx'||$res_ext=='.txt'){
                $res_type='doc';
            }else if($res_ext=='.mp3'||$res_ext=='.wma'){
                $res_type='audio';
            }else if($res_ext=='.mp4'||$res_ext=='.wmv'){
                $res_type='video';
            }else {
                $res_type='';
            }
            $judge=$this->uploadres->insert($name,$theme,$custom_theme,$level,$description,$keyword,$keyphrase,date("Y-m-d   H:i:s"),$address,$author,$author_group,$file_size,$res_type);
            $info['size'] = $data['file_size'];
            $info['type'] = $data['file_type'];
            $info['url'] = $upload_path_url . $data['file_name'];
            $info['thumbnailUrl'] = $upload_path_url . 'thumbs/' . $data['file_name'];
            $info['deleteUrl'] = base_url() . 'upload/deleteImage/' . $data['file_name'];
            $info['deleteType'] = 'DELETE';
            $info['error'] = null;

            $files[] = $info;
            if (IS_AJAX) {
                echo json_encode(array("files" => $files));
            } else {
                $file_data['upload_data'] = $this->upload->data();
                $this->load->view('upload/upload_success', $file_data);
            }
        }
    }

    public function deleteImage() {
        $file=urldecode($this->input->post('file'));
        $file=iconv("utf-8","gbk",$file);
        $success = unlink(FCPATH . 'upload_files/activity/' . $file);
        $file=iconv("gbk","utf-8",$file);
        $info['success'] = $success;
        $info['path'] = base_url() . 'upload_files/activity/' . $file;
        $info['file'] = is_file(FCPATH . 'upload_files/activity/' . $file);
        $this->load->model('uploadres_model','uploadres');
        $author=$this->session->userdata('realname');
        $fileinfo=$this->uploadres->findByName($file);
        $name=$fileinfo->author;
        if($author==$name){
            $judge=$this->uploadres->delete($file);
            if($judge==true){
                $result=100;
            }else{
                $result=102;
            }
        }else{
            $result=103;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));
        /*if (IS_AJAX) {
            echo json_encode(array($info));
        } else {      
            $file_data['delete_data'] = $file;
        }*/
    }

    public function findAllRes(){            
        $author_group=$this->session->userdata('grade');
        $this->load->model('uploadres_model','uploadres');
        $judge=$this->uploadres->search($author_group);
        //print_r($judge);
        foreach ($judge as $row) {
        if(abs($row->file_size)>=1000){         
        $row->file_size=$row->file_size/1000;
        $row->file_size=$row->file_size.'MB';
        }else{
        $row->file_size=$row->file_size.'KB';
        }
        }
      
        $data['data']=$judge;
        print_r(json_encode($data));
    }
   

}