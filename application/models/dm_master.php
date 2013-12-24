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
    public $readable = array();
    
    public $documentation = array(
        'available_methods' => array(
            'GET' => 'No Description',
            'POST' => 'No Description',
            'PUT' => 'No Description',
            'DELETE' => 'No Description'
            ),
        'custom_methods' => array(),
        'description' => ''
    );

    public function __construct($id = NULL) {
        parent::__construct($id);
    }

    public function get_accessible_fields() {
        $out = array();
        foreach ($this->fields as $field) {
            if (!in_array($field, $this->inaccessible)) {
                $out[] = $field;
            }
        }
        return $out;
    }

    public function get_readable_fields() {
        return (count($this->readable)) ? $this->readable : $this->fields;
    }

    /**
     * Attempts to get a translated version of the currently used model.
     * Returns a DataMapper Array of Objects 
     * @param Object $language_object
     * @return Array
     */
    public function get_translated($language_object) {
        $modelName = strtolower(get_called_class());
        $tm_class = "translated_" . $modelName;
        $tm = new $tm_class;
        return $tm->where($modelName . "_id", $this->id)->where('language_id', $language_object->id)->get();
    }

    public function to_assoc($opts = false) {
        $response = array();
        foreach ($this->get_readable_fields() as $field) {
            $response[$field] = $this->{$field};
        }

        //dynamically prepend stuff (mostly for images):
        if ($opts) {
            if (isset($opts['prepend'])) {
                foreach ($opts['prepend'] as $prepend_action) {
                    $response[$prepend_action['field']] = $prepend_action['prepend_with'] . $response[$prepend_action['field']];
                }
            }
            if (isset($opts['additional_nodes'])) {
                foreach ($opts['additional_nodes'] as $key => $value) {
                    $response[$key] = $value;
                }
            }
        }

        return $response;
    }

    public function save_from_obj($obj) {
        foreach($this->get_accessible_fields() as $field) {
            if(isset($obj->{$field})) {
                $this->{$field} = $obj->{$field};
            }
        }
        $this->save();
    }

}