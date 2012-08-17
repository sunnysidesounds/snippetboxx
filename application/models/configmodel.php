<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ConfigModel extends CI_Model {

	public function get_config($ref) {
		
		$sql = 'SELECT config_value FROM configuration WHERE config_title ="'.$ref.'";';
		$query = $this->db->query( $sql );	
		if($query->num_rows()>0){	
			
			foreach ($query->result() as $row){													
				return $row->config_value;				
			
			}	//foreach			
		
		} else {
			//TODO: Change this
			echo 'No Records';
		}		

	} //get_config






} //configmodel
