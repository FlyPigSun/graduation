 
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
        return $template->display($data);
    }
}
 
/* End of file: Twig.php */
/* Location: ./system/libraries/Twig.php */
 