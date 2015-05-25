<?php
class ClassEntity {
    function __construct($id = NULL, $name = NULL) {
        $this->ID   = $id;
        $this->Name = $name;
    }
    
    public $ID;
    public $Name;
}
