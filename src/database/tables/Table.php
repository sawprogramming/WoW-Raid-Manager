<?php 
namespace WRO\Database\Tables;
require_once(ABSPATH.'wp-admin/includes/upgrade.php');

abstract class Table {
	const APP_PREFIX = "WRO_";

	abstract public function CreateTable();

	public function __construct() {
		$name = substr(get_class($this), strrpos(get_class($this), '\\') + 1);
		$end  = strpos($name, "Table");

		// ensure that the extended class is named correctly
		if($end === false) {
			throw new Exception(
				"Classes that extend Table must be named \"<Table Name>Table\".\n"
			  . "Example: \n"
			  . "  For a table with the name \"Employee\", your class would be:\n"
			  . "  class EmployeeTable extends Table {};" 
		    );
		}

		// set the name
		$this->_name = substr($name, 0, $end);
	}

	public function GetName() {
		global $wpdb;
		return $wpdb->prefix . self::APP_PREFIX . $this->_name;
	}

	public function DropTable() {
		global $wpdb;
		return $wpdb->get_results("DROP TABLE IF EXISTS " . $this->GetName() . ";");
	}

	public function TruncateTable() {
		global $wpdb;
		return $wpdb->get_results("TRUNCATE TABLE " . $this->GetName() . ";");
	}
	
	protected $_name;
};