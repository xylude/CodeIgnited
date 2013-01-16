<?php

/*
  Document   : auth
  Created on : May 14, 2012, 10:24:36 AM
  Author     : Jason Crider
  Description:

 */

class Auth extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        if ($this->get->sent()) {
            if ($this->ion_auth->login($this->get->username, $this->get->password)) {
                if ($this->ion_auth->is_admin()) {
                    redirect('welcome'); //if you have a special admin area you can redirect them here:
                } else {
                    redirect('welcome');
                }
            } else {
                $this->data['message'] = $this->ion_auth->errors();
            }
        }
    }

    public function logout() {
        $this->ion_auth->logout();
        redirect('/login');
    }

    public function register() {
        $this->template = 'template/auth';
        if ($this->get->sent()) {
            //preform checks
            if ($this->get->password != $this->get->rpassword) {
                $this->data['message'] = 'Passwords don\'t match.';
            } else {
                $data = $this->get->assoc(array(
                    'username',
                    'email',
                    'password'
                        ));
                if ($this->ion_auth->register($this->get->username, $this->get->password, $this->get->email, $data)) {
                    redirect('/login');
                } else {
                    $this->data['message'] = $this->ion_auth->errors();
                }
            }
        }
    }

}