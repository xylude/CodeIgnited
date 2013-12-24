<?php
/* 
    Document   : docs
    Created on : Dec 20, 2013, 10:09:50 AM
    Author     : Jason Crider
    Description:
        
*/

class Docs extends MY_Controller {
    public function __construct() {
        parent::__construct();
        
    }
    
    public function index() {
        $this->isApi = true;
        $this->load->helper('inflector');
        $models = array('user');
        $this->out['documentation'] = array();
        $this->out['documentation_description'] = array(
            'base_url' => 'http://nchconnect.duethealth.com/[current_api_version]/',
            'current_api_version' => 'api'
        );
        foreach($models as $model) {
            $mod = ucwords($model);
            $m = new $mod;
            $m->get(1);
            $doc = $m->documentation;
            
            $this->out['documentation'][] = array(
                'model_name' => $model,
                'location' => "[base_url]/".plural($mod),
                'writable_fields' => $m->get_accessible_fields(),
                'available_methods' => $doc['available_methods'],
                'sample_response' => $m->to_assoc(),
                'custom_methods' => $doc['custom_methods'],
                'description' => $doc['description']
            );
        }
    }
    
    public function view() {
        $this->template = 'template/blank';
    }
}