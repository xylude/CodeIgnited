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
}

?>
