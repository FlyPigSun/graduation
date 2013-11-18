 
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
require_once(APPPATH . 'third_party/Twig/Autoloader.php');
class Twig {
     
    private $_CI;
    private $_twig;
    private $template_dir = '';
    private $debug = FALSE;
     
    /**
     * construct
     * 
     * @access public
     * @return void
     */
    public function __construct($params = array())
    {
        $this->_CI =& get_instance();
         
        if(count($params) > 0)
        {
            $this->initialize($params);
        }
         
        Twig_Autoloader::register();
         
        $loader = new Twig_Loader_Filesystem($this->template_dir);
        $this->_twig = new Twig_Environment($loader, array(
            'cache' => $this->template_dir . DIRECTORY_SEPARATOR . 'cache',
            'debug' => $this->debug,
        ));
 
        log_message('debug', "Twig Engine Class Initialized");
    }
     
    private function initialize($config)
    {
        foreach ($config as $key => $val)
        {
            if (isset($this->$key))
            {
                $this->$key = $val;
            }
        }
    }
     
    public function render($template, $data = array())
    {
        $template = $this->_twig->loadTemplate($template);
        return $template->render($data);
    }
 
    public function display($template, $data = array())
    {
        $template = $this->_twig->loadTemplate($template);
         
        /* elapsed_time and memory_usage */
        $memory = ( ! function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2) . 'MB';
        $data['elapsed_time'] = $this->_CI->benchmark->elapsed_time('total_execution_time_start', 'total_execution_time_end');
        $data['memory_usage'] = $memory;
         
        $template->display($data);
    }
}
 
/* End of file: Twig.php */
/* Location: ./application/libraries/Twig.php */
 