<?php

class LootItem {
    function __construct($id, $playerId, $item, $raidId, $date) {
        $this->ID       = $id;
        $this->PlayerID = $playerId;
        $this->Item     = $item;
        $this->RaidID   = $raidId;
        $this->Date     = $date;
    }
    
    public $ID;
    public $PlayerID;
    public $Item;
    public $RaidID;
    public $Date;
}
