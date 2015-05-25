<?php
class ItemEntity {
    function __construct($id = NULL, $context = NULL, $level = NULL) {
        $this->ID = $id;
        $this->Context = $context;
        $this->Level = $level;
    }
    
    public $ID;
    public $Context;
    public $Level;
}
