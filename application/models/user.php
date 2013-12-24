<?php
/* 
    Document   : user
    Created on : Dec 24, 2013, 9:17:26 AM
    Author     : Jason Crider
    Description:
        
*/

class User extends Dm_master {
    public $has_one = array();
    public $has_many = array();
    public $inaccessible = array('id');
}