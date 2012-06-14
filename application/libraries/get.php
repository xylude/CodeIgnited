<?php

class Get {

    public $validateKeys = false;

    function __construct() {
        $this->vars();
    }

    function validateKeys() {
        $this->validateKeys = true;
    }

    function sent() {
        if (count($_GET) > 0 || count($_POST) > 0) {
            return true;
        }
        return false;
    }

    function hasKeys() {
        $args = func_get_args();
        $argCount = count($args);

        for ($i = 0; $i < $argCount; $i++) {
            if (!key_exists(func_get_arg($i), $_GET) && !key_exists(func_get_arg($i), $_POST)) {
                return false;
            }
            if ($this->validateKeys) {
                if (!isset($_GET[func_get_arg($i)]) && !isset($_POST[func_get_arg($i)])) {
                    return false;
                } else if (isset($_GET[func_get_arg($i)])) {
                    if (!$_GET[func_get_arg($i)]) {
                        return false;
                    }
                } else if (isset($_POST[func_get_arg($i)])) {
                    if (!$_POST[func_get_arg($i)]) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    function assoc($ignore=false) {
        //distrustful of array_merge
        $r = array();
        foreach ($_POST as $key => $val) {
            $r[$key] = $val;
        }
        foreach ($_GET as $key => $val) {
            $r[$key] = $val;
        }
        if ($ignore) {
            foreach ($ignore as $val) {
                if (key_exists($val, $r)) {
                    unset($r[$val]);
                }
            }
        }

        return $r;
    }

    private function vars() {
        foreach ($_POST as $key => $val) {
            $this->{$key} = $val;
        }
        foreach ($_GET as $key => $val) {
            $this->{$key} = $val;
        }
    }

}