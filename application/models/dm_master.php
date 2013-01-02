<?php

/*
  Document   : dm_master
  Created on : Dec 14, 2012, 8:20:29 AM
  Author     : Jason Crider
  Description: Datamapper extension class. 

 */

class Dm_master extends DataMapper {
    public $has_one = array();
    public $has_many = array();
    public $inaccessible = array();
    
    public function get_accessible_fields() {
        $out = array();
        foreach($this->fields as $field) {
            if(!in_array($field, $this->inaccessible)) {
                $out[] = $field;
            }
        }
        return $out;
    }
}