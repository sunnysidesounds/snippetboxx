<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SearchModel extends CI_Model {

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function search_with_like($term, $limit = ''){
		if(!empty($limit)){
			$limit = 'LIMIT ' . $limit;
		} else {
			$limit = '';
		}
		$sql = 'SELECT * FROM sniplets WHERE sniplet_title LIKE "%'.$term.'%" OR sniplet_content LIKE "%'.$term.'%" ORDER BY sniplet_id '.$limit.';';
		$array = array();
		$query = $this->db->query( $sql );
		if($query->num_rows()>0){
			foreach ($query->result_array() as $row){								
				$array[] = $row;
			} //foreach
			
			return $array;			
		} //if 0	
	} //search_with_like

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function search_with_like_gt($term, $id, $limit = ''){ //NOT CURRENTLY USING
		if(!empty($limit)){
			$limit = 'LIMIT ' . $limit;
		} else {
			$limit = '';
		}		
		$sql = 'SELECT * FROM sniplets WHERE sniplet_title LIKE "%'.$term.'%" OR sniplet_content LIKE "%'.$term.'%" AND sniplet_id > '.$id.' ORDER BY sniplet_id '.$limit.';';
		$array = array();
		$query = $this->db->query( $sql );
		if($query->num_rows()>0){
			foreach ($query->result_array() as $row){								
				$array[] = $row;
			} //foreach
			
			return $array;
	
		} //if 0	
	} //search_with_like_gt

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function search_fulltext_gt($term, $id, $limit = ''){	
		if(!empty($limit)){
			$limit = 'LIMIT ' . $limit;
		} else {
			$limit = '';
		}
		$sql = "SELECT *, MATCH(sniplet_title, sniplet_content) AGAINST ('".$term."') AS score FROM sniplets WHERE MATCH(sniplet_title, sniplet_content) AGAINST('".$term."') AND sniplet_id > ".$id." ORDER BY sniplet_id ".$limit.""; 
		$array = array();				
		$query = $this->db->query( $sql );
		if($query->num_rows()>0){
			//Return query results as a pure array , not an object
			foreach ($query->result_array() as $row){
				$array[] = $row;
			} //foreach
			
			return $array;
			
		} //if 0	
	} //search_fulltext_gt

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function search_fulltext_count($term){	
		$sql = "SELECT count(*), MATCH(sniplet_title, sniplet_content) AGAINST ('".$term."') AS score FROM sniplets WHERE MATCH(sniplet_title, sniplet_content) AGAINST('".$term."') ORDER BY sniplet_id"; 
		$array = array();				
		$query = $this->db->query( $sql );
		if($query->num_rows()>0){
			//Return query results as a pure array , not an object
			foreach ($query->result_array() as $row){
				$array[] = $row;
			} //foreach
			
			return $array;
			
		} //if 0	
	} //search_fulltext_gt

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function search_fulltext($term, $limit = ''){	
		if(!empty($limit)){
			$limit = 'LIMIT ' . $limit;
		} else {
			$limit = '';
		}
		$sql = "SELECT *, MATCH(sniplet_title, sniplet_content) AGAINST ('".$term."') AS score FROM sniplets WHERE MATCH(sniplet_title, sniplet_content) AGAINST('".$term."') ORDER BY sniplet_id ".$limit." "; //ADD LIMITS AND OFFSET FOR PAGINATION
		$array = array();				
		$query = $this->db->query( $sql );
		if($query->num_rows()>0){
			//Return query results as a pure array , not an object
			foreach ($query->result_array() as $row){
				$array[] = $row;
			} //foreach
			
			return $array;
			
		} //if 0	
	} //search_with_like

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function tags_search_fulltext($term){
		$sql = "SELECT *, MATCH(tag_keyword) AGAINST ('".$term."') AS score FROM tags WHERE MATCH(tag_keyword) AGAINST('".$term."')";
		
		$array = array();				
		$query = $this->db->query( $sql );
		if($query->num_rows()>0){
			//Return query results as a pure array , not an object
			foreach ($query->result_array() as $row){
				$array[] = $row;
			} //foreach
			
			return $array;
			
		} //if 0	
	} //search_with_like

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function tags_search_with_like($term){
		$data = array();		
		$sql = 'SELECT tag_keyword FROM tags WHERE tag_keyword LIKE "%'.$term.'%";';
		$query = $this->db->query( $sql );
		if($query->num_rows()>0){
			foreach ($query->result() as $row){
				$json = array();
//				$json['value'] = $term . '-copy';
				$json['value'] = $row->tag_keyword;
				$data[] = $json;			
			} //foreach
			
		} else {
			$json = array();
			$json['value'] = $term;
			$data[] = $json;
		
		}
		
		
		return $data;
		
	} //search_with_like
	
	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function tags_count_ids(){
		$array = array();
		$sql = 'SELECT tag_id, count(*) as counts from sniplets_to_tags GROUP BY tag_id ORDER BY COUNT(*) DESC LIMIT 12;';
		$query = $this->db->query( $sql );
		if($query->num_rows()>0){
			foreach ($query->result() as $row){
								
			//	print_r($row);
				$array[] = $row->tag_id;
			
			} //foreach
			
			return $array;
			
		} //if 0	
	} //all_tags_lids
	
	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function tag_name_by_id($id){	
		if($id != ''){	
			$out = '';
			$sql = 'SELECT tag_keyword from tags WHERE tag_id = '.$id.' ;';
			$query = $this->db->query( $sql );
			if($query->num_rows()>0){
				foreach ($query->result() as $row){
									
				//	print_r($row);
					$out .= $row->tag_keyword;
				
				} //foreach
				
				return $out;
				
			} //if 0			
		}
	} //search_with_like
	

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function tag_id_by_name($name){
		$out = '';
		$sql = 'SELECT tag_id from tags WHERE tag_keyword = "'.$name.'" ;';
		$query = $this->db->query( $sql );
		if($query->num_rows()>0){
			foreach ($query->result() as $row){
				$out .= $row->tag_id;
			
			} //foreach
			
			return $out;
			
		} //if 0	
	} //search_with_like	
	
	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function tag_cloud_list(){
		$array = array();
		$sql = 'SELECT tag_keyword, COUNT(*) as num_items FROM sniplets_to_tags i2t INNER JOIN tags t ON i2t.tag_id = t.tag_id GROUP BY tag_keyword ORDER by num_items DESC;';
		$query = $this->db->query( $sql );
		if($query->num_rows()>0){
			foreach ($query->result() as $row){
				
				$array[] = $row->tag_keyword;
			
			} //foreach
			
			return $array;
			
		} //if 0		
	} //tag_build_cloud
	
	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function sniplet_id_by_tag_id($id, $limit = ''){
		$array = array();		
		if(!empty($limit)){
			$limit = 'LIMIT ' . $limit;
		} else {
			$limit = '';
		}
		
		$sql = 'SELECT DISTINCT sniplet_id from sniplets_to_tags WHERE tag_id = '.$id.' ORDER BY sniplet_id '.$limit.' ;';
		$query = $this->db->query( $sql );
		if($query->num_rows()>0){
			foreach ($query->result() as $row){
				$array[] = $row->sniplet_id;
			
			} //foreach
			
			return $array;
			
		} //if 0	
	} //	

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function sniplet_id_by_tag_id_gt($tag_id, $sniplet_id, $limit = ''){
		$array = array();		
		if(!empty($tag_id)){
		
			if(!empty($limit)){
				$limit = 'LIMIT ' . $limit;
			} else {
				$limit = '';
			}

			$sql = 'SELECT DISTINCT sniplet_id from sniplets_to_tags WHERE tag_id = '.$tag_id.' AND sniplet_id > '.$sniplet_id.' ORDER BY sniplet_id '.$limit.' ;';		
			$query = $this->db->query( $sql );
			if($query->num_rows()>0){
				foreach ($query->result() as $row){
					$array[] = $row->sniplet_id;
				
				} //foreach
				
				return $array;
				
			} //if 0			
		} else {
			return '';
		}
	} //		
	
	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function sniplet_sniplet_id($id){
		$sql = 'SELECT * FROM sniplets WHERE sniplet_id = '.$id.' ORDER BY sniplet_id;';
		$array = array();
		$query = $this->db->query( $sql );
		if($query->num_rows()>0){
			foreach ($query->result_array() as $row){								
				$array[] = $row;
			} //foreach
			
			return $query->result_array();
			
			
			
		} //if 0	
	} //search_with_like
	
	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function sniplet_all($limit = ''){
		if(!empty($limit)){
			$limit = 'LIMIT ' . $limit;
		} else {
			$limit = '';
		}
	
		$sql = 'SELECT * FROM sniplets ORDER BY sniplet_id '.$limit.';';
		$array = array();
		$query = $this->db->query( $sql );
		if($query->num_rows()>0){
			foreach ($query->result_array() as $row){								
				$array[] = $row;
			} //foreach
			
			return $array;
			
			
			
		} //if 0
	}
	
	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function sniplet_greater_than($id, $limit = ''){
		if(!empty($id)){		
			if(!empty($limit)){
				$limit = 'LIMIT ' . $limit;
			} else {
				$limit = '';
			}
			//NOTE: May need to add ORDER BY
			$sql = 'SELECT * FROM sniplets WHERE sniplet_id > '.$id.' '.$limit.' ;';
			$array = array();
			$query = $this->db->query( $sql );
			if($query->num_rows()>0){
				foreach ($query->result_array() as $row){								
					$array[] = $row;
				} //foreach
				
				return $array;
				
				
				
			} //if 0
		
		} else {
			return '';
		}
	
	}

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function random_sniplet(){
		$sql = 'SELECT * FROM sniplets ORDER BY rand() LIMIT 1;';
		$array = array();
		$query = $this->db->query( $sql );
		if($query->num_rows()>0){
			foreach ($query->result_array() as $row){								
				$array[] = $row;
			} //foreach
			
			return $array;
			
			
			
		} //if 0		
	
	}
	
	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function record_count($column, $table){
		$sql = 'SELECT count('.$column.') as count FROM '.$table.';';		
		$query = $this->db->query( $sql );
		if($query->num_rows()>0){
			foreach ($query->result_array() as $row){								
				return $row['count'];
			} //foreach
			
		} //if 0	
	
	}

	/* --------------------------------------------------------------------------------------------------------------------------*/
	public function record_count_where($column, $table, $where, $equals){
		$sql = 'SELECT count('.$column.') as count FROM '.$table.' WHERE '.$where.' = '.$equals.';';		
		$query = $this->db->query( $sql );
		if($query->num_rows()>0){
			foreach ($query->result_array() as $row){								
				return $row['count'];
			} //foreach
			
		} //if 0	
	
	}

	
	


} //SearchModal