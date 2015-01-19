<?php

class LootImport {
    function __construct($id, $playerId, $time) {
        $this->ID           = $id;
        $this->PlayerID     = $playerId;
        $this->LastImported = $time;
    }
    
    public $ID;
    public $PlayerID;
    public $LastImported;
}
