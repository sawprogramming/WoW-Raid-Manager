<?php


class Player {
    function __construct($id, $name, $classId) {
        $this->ID      = $id;
        $this->Name    = $name;
        $this->ClassID = $classId;
    }

    public $ID;
    public $Name;
    public $ClassID;
}
