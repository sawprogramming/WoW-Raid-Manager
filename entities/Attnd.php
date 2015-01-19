<?php

class Attnd {
    function __construct($id, $playerId, $date, $points) {
        $this->ID       = $id;
        $this->PlayerID = $playerId;
        $this->Date     = $date;
        $this->Points   = $points;
    }
    
    public $ID;
    public $PlayerID;
    public $Date;
    public $Points;
}
