<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BaseModel extends CI_Model {

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function clean_db_array($dirty){
		$cleaned = array();
		foreach ($dirty as $key => $value) {
			$cleaned[$key] = $this->db->escape_str($value);
		}
		return $cleaned;
	} //clean_db_array

} //BaseModel