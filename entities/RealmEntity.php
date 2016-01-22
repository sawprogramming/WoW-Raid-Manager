<?php
namespace WRO\Entities;

class RealmEntity {
	public function __construct($slug, $name, $region) {
		$this->Slug = $slug;
		$this->Name = $name;
		$this->Region = $region;
	}

	public $Slug;
	public $Name;
	public $Region;
}