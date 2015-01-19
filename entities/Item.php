<?php

class Item {
    function __construct($id, $context, $level) {
        $this->ID = $id;
        $this->Context = $context;
        $this->Level = $level;
    }
    
    public $ID;
    public $Context;
    public $Level;
}
