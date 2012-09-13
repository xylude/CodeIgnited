<?php

class MY_Controller extends CI_Controller {

    public $template;
    public $data = array('message' => false);
    public $isApi = false;
    public $out = array('success' => false);
    public $view = false;

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');

        if ($this->template == '') {
            $this->template = 'template/default'; //view to load
        }
        if (substr($_SERVER['SERVER_NAME'], 0, 3) == 'api') {
            $this->isApi = true;
            //get and authenticate api key
            if (isset($_POST['api_auth_key'])) {

            } else {
                //header('HTTP/1.1 403 Forbidden');
                //exit;
            }
        } else {
      
        }
    }

    public function _output() {
        if ($this->isApi) {
            if (isset($_GET['format'])) {
                if ($_GET['format'] == 'xml') {
                    $this->output_xml();
                } else {
                    $this->output_json();
                }
            } else {
                $this->output_json();
            }
        } else {
            //$d['content'] = $o;
            if(!$this->view) {
                $d['content'] = $this->load->view($this->router->class.'/'.$this->router->method,$this->data,true);
            } else {
                $d['content'] = $this->load->view($this->view,$this->data,true);
            }
            $template = $this->load->view($this->template, $d, true);
            echo $template;
        }
    }

    private function output_json() {
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json');
        echo json_encode($this->out);
    }
    
    private function output_xml() {
        $outstr = '<?xml version="1.0" encoding="ISO-8859-1"?><response>';
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/xml');
        $outstr.= $this->xmlencode($this->out);
        echo $outstr . '</response>';
    }

    private function xmlencode($arr) {
        $outstr = '';
        foreach ($arr as $k => $v) {
            $k=(is_int($k))?'item':$k;
            if (is_array($v)) {
                $outstr.='<'.$k.'>'.$this->xmlencode($v).'</'.$k.'>';
            } else {
                $outstr.='<' . $k . '>' . str_replace(array("&", "<", ">", "\"", "'"), array("&amp;", "&lt;", "&gt;", "&quot;", "&apos;"), $v) . '</' . $k . '>';
            }
        }
        return $outstr;
    }
}

?>
