<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once( 'base.php' );


class Frontend extends Base {

	public $languages = array("html", "php", "python", "javascript", "perl", "mysql", "bash");
	public $language_default = "html";
	
	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function index(){						
		$this->load->model( 'ConfigModel' );
		$data = $this->set_site_assets();
		$this->dynView( 'frontend/main', 'Sniplets', $data);
	} //index
	
	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function search(){
		//Load modals
		$this->load->model( 'SearchModel' );
		$this->load->model( 'ConfigModel' );
		$this->load->model( 'TrackerModel' );
		$how_many = $this->ConfigModel->get_config('per_page');
		$count_tracker = $this->ConfigModel->get_config('tracker_count');
		$sniplet = $this->input->get('sniplet');
		$records = $this->input->get('get');	
				
		//TAGS display and scroll pagination (if sniplet is empty, must be a tag)
		if(empty($sniplet)){
			$taglet = $this->input->get('taglet');
			$taglet = trim($taglet);
			
			//Setting the taglet id to a cookie
			$taglet_cookie = array('name'   => 'sniplet_taglet', 'value'  => $taglet, 'expire' => '86500', 'domain' => '.snippetboxx.com');					
			set_cookie($taglet_cookie);	
			$searchArray = array();
			//get=tag_limit is not empty
			if(!empty($records)){
				//if not set, we're going to set it
				$taglet_cookie = $this->input->cookie('sniplet_taglet', TRUE);
				$check_list_array = explode(", ", $this->input->cookie('sniplet_tracker', TRUE));		
				$last_id_cookie = end($check_list_array);				
				//Show is greater than / less than
				$snipletIds = $this->SearchModel->sniplet_id_by_tag_id_gt($taglet_cookie, $last_id_cookie, $how_many);
			} else {					
				//Show with initial click
				$snipletIds = $this->SearchModel->sniplet_id_by_tag_id($taglet, $how_many);			
			}
							
			if(!empty($snipletIds)){
			
				$last = end($snipletIds);				
				$check_list_array = explode(", ", $this->input->cookie('sniplet_tracker', TRUE));
				$check_list = $this->input->cookie('sniplet_tracker', TRUE);	
														
				if($check_list != ''){$tracker_data = $check_list . ", " . $last;}
				else {$tracker_data = $last;}
								
				$tracker_reduction_array = explode(", ", $tracker_data);
				$tracker_count = count($tracker_reduction_array);
				
				if($tracker_count == $count_tracker){
					$reset = end($tracker_reduction_array);
					$tracker_data = $reset;
				}			
						
				$sniplet_cookie_tracker = array('name'   => 'sniplet_tracker', 'value'  => $tracker_data, 'expire' => '86500', 'domain' => '.snippetboxx.com');					
				set_cookie($sniplet_cookie_tracker);	
			
				foreach($snipletIds as $ids){
					$sniplet = $this->SearchModel->sniplet_sniplet_id($ids);
					$searchArray[] = $sniplet[0];				
				}			
			}
			
		//SEARCH display and scroll pagination
		} else {
		
			$sniplet = trim($sniplet);
			$sniplet_search_cookie = array('name'   => 'sniplet_search_term', 'value'  => $sniplet, 'expire' => '86500', 'domain' => '.snippetboxx.com');	
			set_cookie($sniplet_search_cookie);

			if(!empty($records)){
				//TODO: Setup pagination
				$sniplet_search_term = $this->input->cookie('sniplet_search_term', TRUE);
				$check_list_array = explode(", ", $this->input->cookie('sniplet_tracker', TRUE));		
				$last_id_cookie = end($check_list_array);		
								
				$snipletIds = $this->SearchModel->search_fulltext_gt($sniplet_search_term, $last_id_cookie, $how_many); //TODO: Fix pagination

				/*Copy of original --> Removed so the scroll pagination would work.
				if(empty($snipletIds)){
					$snipletIds = $this->SearchModel->search_with_like_gt($sniplet_search_term, $last_id_cookie, $how_many); //TODO: Fix pagination
				}	*/									
			} else {
				$snipletIds = $this->SearchModel->search_fulltext($sniplet, $how_many);
				//if no full-text results switch to like based search
				if(empty($snipletIds)){
					$snipletIds = $this->SearchModel->search_with_like($sniplet, $how_many);
				}	
							
				/*Copy of original
				$searchArray = $this->SearchModel->search_fulltext($sniplet);
				//if no full-text results switch to like based search
				if(empty($searchArray)){
					$searchArray = $this->SearchModel->search_with_like($sniplet);
				}
				*/
			} //records
			
			if(!empty($snipletIds)){				
				$last = end($snipletIds);
				$last = $last['sniplet_id'];

				$check_list_array = explode(", ", $this->input->cookie('sniplet_tracker', TRUE));
				$check_list = $this->input->cookie('sniplet_tracker', TRUE);
				
				if($check_list != ''){$tracker_data = $check_list . ", " . $last;}
				else {$tracker_data = $last;}				
				
				$tracker_reduction_array = explode(", ", $tracker_data);
				$tracker_count = count($tracker_reduction_array);				

				if($tracker_count == $count_tracker){
					$reset = end($tracker_reduction_array);
					$tracker_data = $reset;				
				}
				$sniplet_cookie_tracker = array('name'   => 'sniplet_tracker', 'value'  => $tracker_data, 'expire' => '86500', 'domain' => '.snippetboxx.com');					
				set_cookie($sniplet_cookie_tracker);					
				//Send it to display
				$searchArray = $snipletIds;		
			}
					
		} //end of search as you type
		
		//Display results
		
		if(!empty($searchArray)){
			$searchList = $this->search_list($searchArray);
		}	

	} //search 

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function search_list($array){
		$displayPerRow = 5;
		
		if(!empty($array)){
			
			$recordCount = 0;
			$totalCount = count($array);
			
			$view = 0;	
						
			if($view == 0){
				echo '<ul class="search_results_ul">';
					foreach($array as $searchArr){				
						if ($recordCount % $displayPerRow == 0 && $recordCount != 0){
								echo '</ul><ul class="search_results_ul">';
						} 										
						echo '<li class="search_results_li" id="'.$searchArr['sniplet_id'].'">';
							$this->search_items($searchArr);
						echo '</li>';
						$recordCount++;
					} //foreach
				
				echo '</ul>';
				echo '<br />';
			
						
			} elseif($view == 1){
						
				echo '<table border="0" cellspacing="10">';
				echo '<tr>';
					foreach($array as $searchArr){				
						if ($recordCount % $displayPerRow == 0 && $recordCount != 0){
								echo '</tr><tr>';
						} 				
						echo '<td class="search_results_cube" width="300px">';
							$this->search_items($searchArr);
						echo '</td>';
						
						$recordCount++;
					} //foreach
				
				if($totalCount == 2){
					echo '<td>&nbsp;</td>';
				}
				elseif($totalCount == 1) {
					echo '<td>&nbsp;</td>';
					echo '<td>&nbsp;</td>';
				}			
				echo '</tr>';
				echo '</table>';	
				echo '<br />';
			}
		} //if empty	
	}

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function search_items($array, $fancybox = 0, $title_length = 50){
		$this->load->model( 'SnipletModel' );
		$this->load->library('geshilib');
		
		$this->SnipletModel->get_tag_list($array['sniplet_id'], $raw_array = 1);
		
		$formatTitle = substr($array['sniplet_title'], 0, $title_length); 
		
		echo '<ul class="sniplet_data_ul">';
			echo '<li class="sniplet_data_li sniplet_title"><a id="sniplet_id_'.$array['sniplet_id'].'" href="'.$array['sniplet_url'].'" target="_blank" title="'.$array['sniplet_title'].' --> ('.$array['sniplet_url'].')"> ' . $formatTitle . '... </a></li>';
			
			//This outputs syntax highlights depending on tag list 
			$snipletTags = $this->SnipletModel->get_tag_list($array['sniplet_id'], $raw_array = 1);			
			foreach($snipletTags as $snipArr){
				$cleaned = strip_tags(strtolower(trim($snipArr)));				
				if (in_array($cleaned, $this->languages)) {
					//If only one item					
					if(count($snipletTags) == 1){
						$setLanguageType = $cleaned;
					} else {
						$setLanguageType = strip_tags(strtolower(trim($snipletTags[0])));
						break;
					}				
				}else{
					$setLanguageType = $this->language_default;
					break;
				}
								
			} //foreach
								
			echo '<li id="sniplet_content_'.$array['sniplet_id'].'" class="sniplet_data_li sniplet_contents">' . $this->geshilib->highlight(htmlspecialchars_decode($array['sniplet_content']), $setLanguageType) . '</li>';
			$snipletTags = $this->SnipletModel->get_tag_list($array['sniplet_id'], 1);
			if(!empty($snipletTags)){
			$tags = implode(", ", $snipletTags);
			} else {
				$tags = 'N/A';
			}
		//	echo '<li class="sniplet_data_li sniplet_zoom"><img class="sniplet_zoom_img" src="'.base_url().'img/zoom_mag.png" alt="Zoom Sniplet" border="0" /></li>';
			echo '<li class="sniplet_data_li sniplet_tags"><b>Tags:</b> ' . $tags . '</li>';
			echo '<li class="sniplet_data_li sniplet_created sniplet_time"><b>Created at:</b> ' . $array['create_sniplet_time'] . '</li>';
			if(!empty($array['score'])){
				$score = number_format($array['score'], 4, '.', '');
				echo '<li class="sniplet_data_li sniplet_score"><b>Search Score:</b> ' . $score . '</li>';
			}
			if($fancybox == 0){
				echo '<li class="sniplet_data_li copy_it">
					<input type="image" title="View Sniplet Closer!" class="view_sniplet_button" id="sniplet_view_'.$array['sniplet_id'].'" alt="View!" src="'.base_url().'img/zoom_mag.png" >
					<input type="image" title="Highlights text to Copy!" class="copy_sniplet_button" id="'.$array['sniplet_id'].'" alt="Copy!" src="'.base_url().'img/icon_copy.png">
					<input type="image" title="Send email of Sniplet!" class="email_sniplet_button" id="sniplet_email_'.$array['sniplet_id'].'" alt="Email!" src="'.base_url().'img/icon_mail.png">
					</li>';
			} else {
				echo '<li class="sniplet_data_li copy_it">
					<input type="image" title="Highlights text to Copy!" class="copy_sniplet_button copy_sniplet_fancy" id="'.$array['sniplet_id'].'" alt="Copy!" src="'.base_url().'img/icon_copy.png">
					</li>';
			}
			echo '<li class="sniplet_data_li status_message" id="status_message_'.$array['sniplet_id'].'">';
			echo '';
			echo '</li>';
			

		echo '</ul>';
	} //search_items
	
	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function random(){
		$out = '';
		$this->load->model( 'SearchModel' );
		$how_many = $this->input->get('rand');
	
		
		$array = array();
		
		for($i = 1; $i <= $how_many; $i++){
			$random = $this->SearchModel->random_sniplet();			
			$array[] = $random[0];
		}

		//use only unigue values		
		foreach ($array as $k=>$na){
            		$new[$k] = serialize($na);
        			$uniq = array_unique($new);
        		}
        		foreach($uniq as $k=>$ser){
            		$new1[$k] = unserialize($ser);
		}
		echo $this->search_list($new1);

	}
	
	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function display(){
		$this->load->model( 'SearchModel' );
		$this->load->model( 'ConfigModel' );
		$how_many = $this->ConfigModel->get_config('per_page');
		$display_mode = $this->ConfigModel->get_config('display_mode');
		$mode = $this->input->get('set');
		$records = $this->input->get('get');	
		
		$array = array();
		
		if(isset($mode)){
			if($display_mode == 'random'){
				for($i = 1; $i <= $how_many; $i++){
					$value_set = $this->SearchModel->random_sniplet();
					$array[] = $value_set[0];
								
				} //foreach	
				//use only unigue values when doing random shit
				foreach ($array as $k=>$na){
		            $new[$k] = serialize($na);
		        	$uniq = array_unique($new);
		        }
		        foreach($uniq as $k=>$ser){
		            $new1[$k] = unserialize($ser);
				}
			}else if ($display_mode == 'all'){

					$value_set = $this->display_all($records, $how_many);
			
			
			} else {
				$display_mode = 'none';
				echo "Error: " . $display_mode;
			}

				echo $this->search_list($value_set);	
		
		} else {
			echo "Error: mode not set";
		}
	
	} //display

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function display_all($records, $how_many){
	
			//$records, $how_many
				if(!empty($records)){
					$check_list_array = explode(", ", $this->input->cookie('sniplet_tracker', TRUE));
					//Get last id of the list
					$last_id_cookie = end($check_list_array);					
					$value_set = $this->SearchModel->sniplet_greater_than($last_id_cookie, $how_many);			
				} else {
					$value_set = $this->SearchModel->sniplet_all($how_many);					
				}				
				if(!empty($value_set)){
				
					$last = end($value_set);			
					
					//echo '<pre>';
					//print_r($value_set);
					//echo '</pre>';
							
					$last = $last['sniplet_id'];
								
					$check_list_array = explode(", ", $this->input->cookie('sniplet_tracker', TRUE));
					$check_list = $this->input->cookie('sniplet_tracker', TRUE);
					
					
					//If last value is not in our cookie array
					if (!in_array($last, $check_list_array)) {
											
						if($check_list != ''){
							$tracker_data = $check_list . ", " . $last;
						}else {
							$tracker_data = $last;
						}
						$sniplet_cookie_tracker = array('name'   => 'sniplet_tracker', 'value'  => $tracker_data, 'expire' => '86500', 'domain' => '.snippetboxx.com');					
						set_cookie($sniplet_cookie_tracker);	
																			
					} //in_array		
					
						return $value_set;
						
				} //empty value_set
	
	} //display_all
	
	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function tags(){
		
		$this->load->model( 'SearchModel' );
		$this->load->model( 'SnipletModel' );
		$taglet = $this->input->get('sniplet');
		$taglet = trim($taglet);
		
		//Multi-dimensional->full-text
		$searchArray = $this->SearchModel->search_fulltext($taglet);
		//if no full-text results switch to like based search
		if(empty($searchArray)){
			$searchArray = $this->SearchModel->search_with_like($taglet);
		}
		
		
		$multiArray = array();
		if(!empty($searchArray)){
			foreach($searchArray as $array){
				$snipletTags = $this->SnipletModel->get_tag_list($array['sniplet_id'], 0);				
				if(!empty($snipletTags)){
					foreach($snipletTags as $sparray){
						$multiArray[] = $sparray;
					
		
					} //foreach
				}
			} //foreach
		}
		
		//Strip duplicates
		$multiArray = array_unique($multiArray);
		//Get the top ten of the search tags
		$topTen = array_slice($multiArray, 0, 10);
		
		$tTen = array();
		foreach($topTen as $arr){
			$arr = $this->SearchModel->tag_id_by_name($arr);
			//Build id's array to send to be formatted in header
			$tTen[] = $arr;
		}
				
		
		echo $this->tags_items($tTen);

	}// tags
		
	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function box($id){
		$id = substr( $id, strrpos( $id, '_' )+1 );	
		$this->load->model( 'SearchModel' );
		$snipletArray = $this->SearchModel->sniplet_sniplet_id($id);
		$this->search_items($snipletArray[0], 1, 500);
	
	} //box

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function about(){
		$out = '';
		$out .= '<div id="sniplet_about" class="sniplet_min_height">';
		$out .= '<ul class="sniplet_about_ul">';
			
			$out .= '<li class="snplet_about_li">';
			$out .= '<h3>The idea behind the site.</h3>';
			$out .= '<span>Snippetboxx.com was created to allow people of the web to easily access, store and use "sniplets" of useful information and functional code. 
			By having a centralized place that can be accessed anywhere in the world from a desktop computer to a mobile phone. 
			This allows the user to use this information more efficiently and become more productive.</span> <br /><br /> Draft, more coming soon!';
			
			$out .= '</li>';

			//$out .= '<li class="snplet_about_li">';
			//$out .= '<h3>Who is this site for?</h3>';
			//$out .= '<span><Content></span>';
			//$out .= '</li>';

			//$out .= '<li class="snplet_about_li">';
			//$out .= '<h3>How do you use this site?</h3>';
			//$out .= '<span><Content></span>';
			//$out .= '</li>';

			//$out .= '<li class="snplet_about_li">';
			//$out .= '<h3>A step further.</h3>';
			//$out .= '<span><Content></span>';
			//$out .= '</li>';
		
		
		$out .= '</ul>';
		$out .= '</div>';
		
		echo $out;
		
	
	
	}//about
	
	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function tag_cloud($order = ''){
		$this->load->model( 'SearchModel' );
		$tagNamesArray = $this->SearchModel->tag_cloud_list();
		
		
		if($order == 'ascending'){
			sort($tagNamesArray);
		}
		elseif($order == 'descending'){
			rsort($tagNamesArray);
		} else {
			$tagNamesArray = $tagNamesArray;
		}
		
		$cloudArr = array();
	
		foreach($tagNamesArray as $array){	
			$id = $this->SearchModel->tag_id_by_name($array);
			$count = $this->SearchModel->record_count_where('sniplet_id', 'sniplets_to_tags', 'tag_id', $id);
			$cloudArr[] = '<a href="#" id="at_'.$id.'" class="at_tags">'.$array.' ('.$count.')</a>';
		//print_r($theCloud);
		}
		
		return $cloudArr;
	
	} //tag_cloud
	
	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function all_tags(){
		$this->load->model( 'ConfigModel' );
		$displayPerRow = $this->ConfigModel->get_config('tags_per_row');
		
		$sort = $this->input->get('get');
		$array = $this->tag_cloud($sort);
		
		echo '<div id="sniplet_all_tags" class="sniplet_min_height">';
		//echo '<h3>All Tags</h3>';
		if(!empty($array)){
			$recordCount = 0;
			$totalCount = count($array);
			echo '<ul class="all_tags_ul">';
				foreach($array as $searchArr){	
					if ($recordCount % $displayPerRow == 0 && $recordCount != 0){
						echo '</ul><br /><ul class="all_tags_ul">';
					}
					echo '<li class="all_tags_li">';
						echo $searchArr;
					echo '</li>';
					
					$recordCount++;
				}
			echo '</ul>';
		
		}
		echo '</div>';
		
	} //all_tags
		
	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function sort_json(){
		$sortArray = array('ascending' => 'a - z', 'descending' => 'z - a');
		$jsonEncodeTypes = json_encode($sortArray);
		echo $jsonEncodeTypes;	
	} //sort_json
	
	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function count_tags(){
		$this->load->model( 'SearchModel' );
	    $count = $this->SearchModel->record_count('tag_id', 'tags');
		$jsonEncodeTypes = json_encode($count);
		echo $jsonEncodeTypes;
	} //count_tags
	
	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function count_sniplets(){
		$this->load->model( 'SearchModel' );
	    $count = $this->SearchModel->record_count('sniplet_id', 'sniplets');
		$jsonEncodeTypes = json_encode($count);
		echo $jsonEncodeTypes;
	} //count_sniplets

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function count_results($id){
		$this->load->model( 'SearchModel' );
	    $count = $this->SearchModel->record_count_where('tag_id', 'sniplets_to_tags', 'tag_id', $id);
		$jsonEncodeTypes = json_encode($count);
		echo $jsonEncodeTypes;
	} //count_results

	/* --------------------------------------------------------------------------------------------------------------------------*/	
	public function count_search_results($term){
		$this->load->model( 'SearchModel' );
	    $count = $this->SearchModel->search_fulltext_count($term);
		$jsonEncodeTypes = json_encode($count);
		echo $jsonEncodeTypes;
	} //count_search_results


} //Frontend
