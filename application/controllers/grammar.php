
<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Grammar extends MY_Controller {

    public function showResult(){
        $keyword=$this->input->post('keyword');
        if($keyword!=''){
            $this->load->model('grammar_model','grammar');
            $info=$this->grammar->find($keyword);
            if($info!=null){
                $data['data']=$info;
                $result=100;            
            }else{
                $result=102;
            }
        }else{
            $result=104;
        }
        $data['errcode']=$result;
        print_r(json_encode($data));
    }

}
?>