<?php

abstract class Service {
    public function Authenticate($roles) {
        return array_intersect($roles, wp_get_current_user()->roles);
    }
}
