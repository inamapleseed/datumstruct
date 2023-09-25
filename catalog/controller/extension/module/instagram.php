<?php
class ControllerExtensionModuleInstagram extends Controller {
	public function index($setting) {
		
		$file = DIR_APPLICATION . 'view/javascript/instagram/css/mycustom.css';
		$filename = 'instagram.log';
		
		$data['heading_title'] = $setting['instagram_module_name'];

		$this->document->addStyle('catalog/view/javascript/instagram/slick/slick.css');
		$this->document->addStyle('catalog/view/javascript/instagram/slick/slick-theme.css');
	
		if( file_exists($file) ){
			$this->document->addStyle('catalog/view/javascript/instagram/css/mycustom.css');
		}else{			
			$this->document->addStyle('catalog/view/javascript/instagram/css/custom.css');
		}

		$this->document->addScript('catalog/view/javascript/instagram/slick/slick.min.js');
		
		//$json_link="https://api.instagram.com/v1/users/self/media/recent/?";
		$json_link= 'https://graph.instagram.com/'.$setting['instagram_user_id'].'/media?fields=media_url,thumbnail_url,caption,media_type,timestamp,like_count,permalink,children{media_url,media_type,timestamp,permalink,thumbnail_url}&limit=' .$setting['instagram_photo_amount'];

		$data['log'] = '';
		$data['error_warning'] = '';

		// $cUrl = curl_init();

		// curl_setopt($cUrl, CURLOPT_URL, $json_link);
		
		// curl_setopt($cUrl, CURLOPT_SSL_VERIFYPEER, false);
		// curl_setopt($cUrl, CURLOPT_SSL_VERIFYHOST, false);

		// curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, 1);

		// $returnCurl = curl_exec($cUrl);
		
		// if($returnCurl){
			// $json = $returnCurl;
			// $instagram = json_decode($json, true, 512, JSON_BIGINT_AS_STRING);
			
			// foreach ($instagram['data'] as $instagram ) {
				// $data['instagrams'][] = array(
					// 'href'  => $instagram['link'],
					// 'likes' => $instagram['likes']['count'],				
					// 'img' 	=> str_replace('http://', 'https://', $instagram['images']['standard_resolution']['url']),
					// 'text'	=> $instagram['caption']['text']
				// );
			// }		

			$instagram_arr = $this->getCacheOrJson($json_link, $filename, $this->for_sbi_maybe_clean( $setting['instagram_access_token'] ), $setting['module_id']);
		//	$instagram_arr = $this->getCacheOrJson($json_link, $filename, $setting['instagram_access_token'], $setting['module_id']);
			
		//	debug($instagram_arr);
			//$instagram = json_decode($json, true, 512, JSON_BIGINT_AS_STRING);
			
			if(isset($instagram_arr['data'])) {
				foreach ($instagram_arr['data'] as $instagram ) {
					$data['instagrams'][] = array(
						//'href'  => $instagram['link'],
						'href'  => $instagram['permalink'],
						//'likes' => $instagram['likes']['count'],				
						'likes' => '',				
						//'img' 	=> str_replace('http://', 'https://', $instagram['images']['standard_resolution']['url']),
						'img' 	=> $instagram['media_url'],
						'media_type' =>  $instagram['media_type'],
						//'text'	=> $instagram['caption']['text']
						'text'	=> isset($instagram['caption']) ? $instagram['caption'] : '',
					);
				}
			}

			$data['entry_instagram'] 	= html($setting['instagram_module_name']);

			$data['slidesToShow'] 		= $setting['instagram_plugin_slide_show'];		
			$data['slidesToScroll'] 	= $setting['instagram_plugin_slide_scroll'];
			$data['autoplay'] 			= $setting['instagram_plugin_auto_play'];
			$data['autoplaySpeed'] 		= $setting['instagram_plugin_auto_play_speed'];
			$data['dots'] 				= $setting['instagram_plugin_dots'];
			$data['arrows'] 			= $setting['instagram_plugin_arrows'];

			$data['heart_color'] 		= $setting['instagram_heart_color'];
			$data['heart_text_color'] 	= $setting['instagram_text_heart_color'];

			$data['slidesToShowTablet'] 	= $setting['instagram_plugin_slide_show_tablet'];
			$data['slidesToScrollTablet'] 	= $setting['instagram_plugin_slide_scroll_tablet'];
			$data['slidesToShowCelphone'] 	= $setting['instagram_plugin_slide_show_celphone'];
			$data['slidesToScrollCelphone'] = $setting['instagram_plugin_slide_scroll_celphone'];
			$data['center_mode']			= $setting['instagram_center_mode'];

			$data['use_plugin']				= $setting['instagram_use_plugin'];

			$data['color'] = $setting['instagram_arrow_color'];
			$data['text_align'] = $setting['instagram_text_align'];
			$data['hover_effect'] = $setting['instagram_hover_heart'];
			
			return $this->load->view('extension/module/instagram.tpl', $data);			
		// }else{
			// $this->log = new log($filename);
			// $this->log->write(file_get_contents($json_link));
		// }
	}

    /*** new functions added ***/
	function for_sbi_maybe_clean( $maybe_dirty ) {
		if ( substr_count ( $maybe_dirty , '.' ) < 3 ) {
			return str_replace( '634hgdf83hjdj2', '', $maybe_dirty );
		}
	
		$parts = explode( '.', trim( $maybe_dirty ) );
		$last_part = $parts[2] . $parts[3];
		$cleaned = $parts[0] . '.' . base64_decode( $parts[1] ) . '.' . base64_decode( $last_part );
	
		return $cleaned;
	}

	function refreshIGToken($access_token) {
		$url = 'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token='.$this->for_sbi_maybe_clean($access_token);
	//	$url = 'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token='.$access_token;
		
		$return_access_token =  $this->call_curl($url);
		$new_access_token = '';
		if(!empty($return_access_token)) {
			$new_access_token_arr = json_decode($return_access_token, true);
			$new_access_token = $new_access_token_arr['access_token'];
		}
	//	debug($new_access_token);
		return $new_access_token;
	}

	function call_curl($url) {
		$cUrl = curl_init();
		curl_setopt($cUrl, CURLOPT_URL, $url);
		curl_setopt($cUrl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($cUrl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, 1);
		$returnCurl = curl_exec($cUrl);
		return $returnCurl;
	}
	
	function updateModuleAccessToken($module_id, $access_token) {
	    $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module` WHERE `module_id` = '" . (int)$module_id . "'");

        $module_data = array();
		if ($query->row) {
			$module_data = json_decode($query->row['setting'], true);
		} 
		
		unset($module_data['instagram_access_token']);
		
		$module_data['instagram_access_token'] = $access_token;
		$this->db->query("UPDATE `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($module_data['name']) . "', `setting` = '" . $this->db->escape(json_encode($module_data)) . "' WHERE `module_id` = '" . (int)$module_id . "'");
	}
	
	function getCacheOrJson($json_link, $filename, $access_token, $module_id) {
		$cacheFile = DIR_CACHE . DIRECTORY_SEPARATOR . 'cache-ig-'.md5('cache-ig-data');

 		if (file_exists($cacheFile)) {
 			$fh = fopen($cacheFile, 'r');
 			$cacheTime = trim(fgets($fh));

 			// if data was cached recently, return cached data
 			if ($cacheTime > strtotime('-24 hours')) {
 			 	//return json_decode(fread($fh, filesize($cacheFile)), true);
 				return json_decode(fread($fh, filesize($cacheFile)), true, 512, JSON_BIGINT_AS_STRING);
 			}
 			else {
 			// refresh token when cache expired
				$new_access_token = $this->refreshIGToken($access_token);
				if($new_access_token) {
				    $this->updateModuleAccessToken($module_id, $new_access_token);
				}
 			}

			// else delete cache file
 			fclose($fh);
 			unlink($cacheFile);
 		}
		//debug($new_access_token);
		
		// if no new access token then use current
		if(!isset($new_access_token)) {
		    $new_access_token = $access_token;
		}

		$json_link .= '&access_token=' . $new_access_token;

        //debug($json_link);

		$returnCurl = $this->call_curl($json_link);
		
		//debug($returnCurl);
		
		if($returnCurl){
			//$fh = fopen($cacheFile, 'w');
			//fwrite($fh, time() . "\n");
			//fwrite($fh, json_encode($json));
			//fclose($fh);
			
			file_put_contents($cacheFile, time()."\n".$returnCurl);
			
			return json_decode($returnCurl, true, 512, JSON_BIGINT_AS_STRING);
		}else{
			$this->log = new log($filename);
			$this->log->write(file_get_contents($json_link));
			
			return array();
		}
	}
	/*** new functions added ***/
}