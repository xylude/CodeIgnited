<?php

class MY_Controller extends CI_Controller {

    public $template;
    public $data = array('message' => false);
    public $isApi = false;
    public $out = array('success' => true);
    public $view = false;

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');

        if ($this->template == '') {
            $this->template = 'template/default'; //view to load
        }

        $code = (isset($this->get->language)) ? $this->get->language : 'en';
        $this->language = new Language();
        $this->language->get_by_code1($code);

        $this->data['language'] = & $this->language; //alias

        if (!$this->input->is_cli_request()) {
            if (substr_count($this->uri->segment(1), 'api') > 0) {
                $this->isApi = true;
                //get and authenticate api key
                $this->data = json_decode(file_get_contents('php://input'));
            }
        } else {
            $this->isCLI = true;
        }
    }

    protected function validate() {
        if (!$this->ion_auth->logged_in()) {
            redirect('/login');
        } else {
            $user = $this->ion_auth->user()->row();
            $this->user = new User();
            $this->user->get_by_email($user->email);
            $this->data['user'] = & $this->user;
            if (!$this->ion_auth->is_admin()) {
                //do admin stuff
            }
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
            foreach ($this->data as $k => $v) {
                $d[$k] = $v;
            }
            if (!$this->view) {
                $d['content'] = $this->load->view($this->router->directory . $this->router->class . '/' . $this->router->method, $this->data, true);
            } else {
                $d['content'] = $this->load->view($this->view, $this->data, true);
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
            $k = (is_int($k)) ? 'item' : $k;
            if (is_array($v)) {
                $outstr.='<' . $k . '>' . $this->xmlencode($v) . '</' . $k . '>';
            } else {
                $outstr.='<' . $k . '>' . str_replace(array("&", "<", ">", "\"", "'"), array("&amp;", "&lt;", "&gt;", "&quot;", "&apos;"), $v) . '</' . $k . '>';
            }
        }
        return $outstr;
    }

    protected function check_input_vars() {
        $args = func_get_args();
        $argCount = count($args);

        for ($i = 0; $i < $argCount; $i++) {
            if (!isset($this->data->{$args[$i]})) {
                $this->error_handler->output(200);
            }
        }
    }

    protected function upload_file($input_element, $subdirectory = false, $save_name = false) {
        if (isset($_FILES[$input_element]) && $_FILES[$input_element]['name'] != '') {
            $ext = basename($_FILES[$input_element]['name']);
            $ext = explode('.', $ext);
            $ext = $ext[count($ext) - 1];

            $config = array(
                'upload_path' => UPLOAD_LOCATION . $subdirectory,
                'allowed_types' => '*',
                'overwrite' => true,
                'max_size' => 1000000,
                'max_width' => 100000,
                'max_height' => 100000,
                'file_name' => ($save_name) ? $save_name . '.' . $ext : time() . '.' . $ext
            );

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload($input_element)) {
                return array('error' => true, 'message' => $this->upload->display_errors('', ''));
            } else {
                $data = $this->upload->data();
                return $data;
            }
        } else {
            return false;
        }
    }

    protected function send_push_message() {
        
    }

    protected function send_email() {
        
    }
}