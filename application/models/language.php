<?php
/* 
    Document   : language
    Created on : Dec 24, 2013, 9:14:58 AM
    Author     : Jason Crider
    Description:
        
*/

class Language extends Dm_master {
    public $has_one = array();
    public $has_many = array();
    public $inaccessible = array('id');
}