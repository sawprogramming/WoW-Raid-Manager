<?php
namespace WRO\Entities;

class ImportHistoryEntity {
    function __construct($id = NULL, $playerId = NULL, $time = NULL) {
        $this->ID           = $id;
        $this->PlayerID     = $playerId;
        $this->LastImported = $time;
    }
    
    public $ID;
    public $PlayerID;
    public $LastImported;
}
