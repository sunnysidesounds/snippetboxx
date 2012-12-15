<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'basemodel.php' );

class ConfigModel extends BaseModel {

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function get_config($ref) {
		$sql = 'SELECT config_value FROM configuration WHERE config_title =?;';
		$query = $this->db->query( $sql, array($ref));	
		if($query->num_rows()>0){
			foreach ($query->result() as $row){													
				return $row->config_value;				
			}	//foreach			
		} else {
			log_message('error', 'get_config failed for ref: '+$ref+' [configmodel/get_config]');
		}		
	} //get_config
	
} //configmodel
