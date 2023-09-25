<?php
class ModelCatalogNews extends Model {
	public function addNews($data, $ifcopy = 0) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "sb_news SET status = '" . (int)$data['status'] . "', acom = '" . (int)$data['acom'] . "', nauthor_id = '" . (int)$data['nauthor_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', gal_thumb_w = '" . (int)$data['gal_thumb_w'] . "', gal_thumb_h = '" . (int)$data['gal_thumb_h'] . "', gal_popup_w = '" . (int)$data['gal_popup_w'] . "', gal_popup_h = '" . (int)$data['gal_popup_h'] . "', gal_slider_h = '" . (int)$data['gal_slider_h'] . "', gal_slider_w = '" . (int)$data['gal_slider_w'] . "', gal_slider_t = '" . (int)$data['gal_slider_t'] . "', date_pub = '" . $this->db->escape($data['date_pub']) . "', date_added = '" . $this->db->escape($data['date_added']) . "', date_updated = '" . $this->db->escape($data['date_updated']) . "'");
		$news_id = $this->db->getLastId(); 
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "sb_news SET image = '" . $this->db->escape($data['image']) . "' WHERE news_id = '" . (int)$news_id . "'");
		}
		
		if (isset($data['image2'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "sb_news SET image2 = '" . $this->db->escape($data['image2']) . "' WHERE news_id = '" . (int)$news_id . "'");
		}
		
		if (isset($data['mask']) && text($data['mask']) != '') {
			$this->db->query("INSERT INTO " . DB_PREFIX . "brochure_pdf SET brochure_id='". (int)$news_id ."', filename = '" . $this->db->escape($data['filename']) . "', mask = '" . $this->db->escape($data['mask']) . "'");
		}
		
		if (isset($data['brochure_pdf_id'])) {
			if($data['mask']){
				$this->db->query("INSERT INTO  `". DB_PREFIX ."brochure_pdf` SET brochure_id='". (int)$news_id ."', mask='" . $this->db->escape($data['mask']) . "', filename='" . $this->db->escape($data['filename']) ."'");
			}
		}

		foreach (@$data['news_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "sb_news_description SET news_id = '" . (int)$news_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_desc = '" . $this->db->escape($value['meta_desc']) . "', meta_key = '" . $this->db->escape($value['meta_key']) . "', ctitle = '" . $this->db->escape($value['ctitle']) . "', ntags = '" . $this->db->escape($value['ntags']) . "', description2 = '" . $this->db->escape($value['description2']) . "', cfield1 = '" . $this->db->escape($value['cfield1']) . "', cfield2 = '" . $this->db->escape($value['cfield2']) . "', cfield3 = '" . $this->db->escape($value['cfield3']) . "', cfield4 = '" . $this->db->escape($value['cfield4']) . "'");
		}
		if (isset($data['news_store'])) {
			foreach ($data['news_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "sb_news_to_store SET news_id = '" . (int)$news_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		if (isset($data['news_group'])) {
			foreach ($data['news_group'] as $group_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "sb_news_to_group SET news_id = '" . (int)$news_id . "', group_id = '" . (int)$group_id . "'");
			}
		}
		if (isset($data['news_ncategory'])) {
			foreach ($data['news_ncategory'] as $ncategory_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "sb_news_to_ncategory SET news_id = '" . (int)$news_id . "', ncategory_id = '" . (int)$ncategory_id . "'");
			}
		}
		if (isset($data['news_related'])) {
			foreach ($data['news_related'] as $related_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "sb_news_related SET news_id = '" . (int)$news_id . "', product_id = '" . (int)$related_id . "'");
			}
		}
		
		if (isset($data['news_nrelated'])) {
			foreach ($data['news_nrelated'] as $nrelated_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "sb_news_related SET news_id = '389657" . (int)$news_id . "', product_id = '" . (int)$nrelated_id . "'");
			}
		}
		
		if (isset($data['news_gallery'])) {
			foreach ($data['news_gallery'] as $news_gallery) {
				if ($ifcopy) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "sb_news_gallery SET news_id = '" . (int)$news_id . "', image = '" . $this->db->escape(html_entity_decode($news_gallery['image'], ENT_QUOTES, 'UTF-8')) . "', text = '" . $this->db->escape($news_gallery['text']) . "', sort_order = '" . (int)$news_gallery['sort_order'] . "'");
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "sb_news_gallery SET news_id = '" . (int)$news_id . "', image = '" . $this->db->escape(html_entity_decode($news_gallery['image'], ENT_QUOTES, 'UTF-8')) . "', text = '" . $this->db->escape(serialize($news_gallery['text'])) . "', sort_order = '" . (int)$news_gallery['sort_order'] . "'");
				}
			}
		}
		
		if (isset($data['news_video'])) {
			foreach ($data['news_video'] as $news_video) {
				if ($ifcopy) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "sb_news_video SET news_id = '" . (int)$news_id . "', video = '" . $this->db->escape(html_entity_decode($news_video['video'], ENT_QUOTES, 'UTF-8')) . "', text = '" . $this->db->escape($news_video['text']) . "', width = '" . (int)$news_video['width'] . "', height = '" . (int)$news_video['height'] . "', sort_order = '" . (int)$news_video['sort_order'] . "'");
				} else {
					$this->db->query("INSERT INTO " . DB_PREFIX . "sb_news_video SET news_id = '" . (int)$news_id . "', video = '" . $this->db->escape(html_entity_decode($news_video['video'], ENT_QUOTES, 'UTF-8')) . "', text = '" . $this->db->escape(serialize($news_video['text'])) . "', width = '" . (int)$news_video['width'] . "', height = '" . (int)$news_video['height'] . "', sort_order = '" . (int)$news_video['sort_order'] . "'");
				}
			}
		}
		if (isset($data['news_layout'])) {
			foreach ($data['news_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "sb_news_to_layout SET news_id = '" . (int)$news_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'news_id=" . (int)$news_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$this->cache->delete('news');
	}

	public function getBrochure($news_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "brochure_pdf where brochure_id = '". (int)$news_id . "'");

		return $query->row;
	}
	
	public function editNews($news_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "sb_news SET status = '" . (int)$data['status'] . "', acom = '" . (int)$data['acom'] . "', nauthor_id = '" . (int)$this->request->post['nauthor_id'] . "', sort_order = '" . (int)$data['sort_order'] . "', gal_thumb_w = '" . (int)$data['gal_thumb_w'] . "', gal_thumb_h = '" . (int)$data['gal_thumb_h'] . "', gal_popup_w = '" . (int)$data['gal_popup_w'] . "', gal_popup_h = '" . (int)$data['gal_popup_h'] . "', gal_slider_h = '" . (int)$data['gal_slider_h'] . "', gal_slider_w = '" . (int)$data['gal_slider_w'] . "', gal_slider_t = '" . (int)$data['gal_slider_t'] . "', date_pub = '" . $this->db->escape($data['date_pub']) . "', date_added = '" . $this->db->escape($data['date_added']) . "', date_updated = '" . $this->db->escape($data['date_updated']) . "' WHERE news_id = '" . (int)$news_id . "'");
		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "sb_news SET image = '" . $this->db->escape($data['image']) . "' WHERE news_id = '" . (int)$news_id . "'");
		}
		if (isset($data['image2'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "sb_news SET image2 = '" . $this->db->escape($data['image2']) . "' WHERE news_id = '" . (int)$news_id . "'");
		}
		

		$this->db->query("DELETE FROM `". DB_PREFIX ."brochure_pdf` WHERE brochure_id='" . (int)$news_id . "'");

		if (isset($data['brochure_pdf_id'])) {
			if($data['mask']){
				$this->db->query("INSERT INTO  `". DB_PREFIX ."brochure_pdf` SET brochure_id='". (int)$news_id ."', mask='" . $this->db->escape($data['mask']) . "', filename='" . $this->db->escape($data['filename']) ."'");
			}
		}
		
		// if (isset($data['brochure_pdf_id']) && $data['brochure_pdf_id'] ) {
		// 	if ( $data['mask'] ) {
				

		// 		// Update
		// 		$this->db->query("UPDATE `". DB_PREFIX ."brochure_pdf` SET brochure_id='". (int)$news_id ."', mask='" . $this->db->escape($data['mask']) . "', filename='" . $this->db->escape($data['filename']) . "' WHERE brochure_pdf_id='" . (int)$data['brochure_pdf_id'] . "'");
		// 	}
		// 	else{
		// 		// Delete
		// 		$this->db->query("DELETE FROM `". DB_PREFIX ."brochure_pdf` WHERE brochure_pdf_id='" . (int)$data['brochure_pdf_id'] . "'");
		// 	}
		// }
		// elseif ( $data['mask'] ) {
		// 	$this->db->query("INSERT INTO " . DB_PREFIX . "brochure_pdf SET brochure_id='". (int)$brochure_id ."', filename = '" . $this->db->escape($data['filename']) . "', mask = '" . $this->db->escape($data['mask']) . "'");
		// }

		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_news_description WHERE news_id = '" . (int)$news_id . "'");
		foreach (@$data['news_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "sb_news_description SET news_id = '" . (int)$news_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_desc = '" . $this->db->escape($value['meta_desc']) . "', meta_key = '" . $this->db->escape($value['meta_key']) . "', ctitle = '" . $this->db->escape($value['ctitle']) . "', ntags = '" . $this->db->escape($value['ntags']) . "', description2 = '" . $this->db->escape($value['description2']) . "', cfield1 = '" . $this->db->escape($value['cfield1']) . "', cfield2 = '" . $this->db->escape($value['cfield2']) . "', cfield3 = '" . $this->db->escape($value['cfield3']) . "', cfield4 = '" . $this->db->escape($value['cfield4']) . "'");
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_news_to_store WHERE news_id = '" . (int)$news_id . "'");

		if (isset($data['news_store'])) {
			foreach ($data['news_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "sb_news_to_store SET news_id = '" . (int)$news_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_news_to_group WHERE news_id = '" . (int)$news_id . "'");

		if (isset($data['news_group'])) {
			foreach ($data['news_group'] as $group_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "sb_news_to_group SET news_id = '" . (int)$news_id . "', group_id = '" . (int)$group_id . "'");
			}
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_news_to_ncategory WHERE news_id = '" . (int)$news_id . "'");
		
		if (isset($data['news_ncategory'])) {
			foreach ($data['news_ncategory'] as $ncategory_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "sb_news_to_ncategory SET news_id = '" . (int)$news_id . "', ncategory_id = '" . (int)$ncategory_id . "'");
			}
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_news_related WHERE news_id = '" . (int)$news_id . "'");

		if (isset($data['news_related'])) {
			foreach ($data['news_related'] as $related_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "sb_news_related SET news_id = '" . (int)$news_id . "', product_id = '" . (int)$related_id . "'");
			}
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_news_related WHERE news_id = '389657" . (int)$news_id . "'");
		
		if (isset($data['news_nrelated'])) {
			foreach ($data['news_nrelated'] as $nrelated_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "sb_news_related SET news_id = '389657" . (int)$news_id . "', product_id = '" . (int)$nrelated_id . "'");
			}
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_news_gallery WHERE news_id = '" . (int)$news_id . "'");
		if (isset($data['news_gallery'])) {
			foreach ($data['news_gallery'] as $news_gallery) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "sb_news_gallery SET news_id = '" . (int)$news_id . "', image = '" . $this->db->escape(html_entity_decode($news_gallery['image'], ENT_QUOTES, 'UTF-8')) . "', text = '" . $this->db->escape(serialize($news_gallery['text'])) . "', sort_order = '" . (int)$news_gallery['sort_order'] . "'");
			}
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_news_video WHERE news_id = '" . (int)$news_id . "'");
		if (isset($data['news_video'])) {
			foreach ($data['news_video'] as $news_video) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "sb_news_video SET news_id = '" . (int)$news_id . "', video = '" . $this->db->escape(html_entity_decode($news_video['video'], ENT_QUOTES, 'UTF-8')) . "', text = '" . $this->db->escape(serialize($news_video['text'])) . "', width = '" . (int)$news_video['width'] . "', height = '" . (int)$news_video['height'] . "', sort_order = '" . (int)$news_video['sort_order'] . "'");
			}
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_news_to_layout WHERE news_id = '" . (int)$news_id . "'");
		
		if (isset($data['news_layout'])) {
			foreach ($data['news_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "sb_news_to_layout SET news_id = '" . (int)$news_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'news_id=" . (int)$news_id. "'");
		
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'news_id=" . (int)$news_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		$this->cache->delete('news');
	}
    public function copyNews($news_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "sb_news n LEFT JOIN " . DB_PREFIX . "sb_news_description nd ON (n.news_id = nd.news_id) WHERE n.news_id = '" . (int)$news_id . "' AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		if ($query->num_rows) {
			$data = array();
			
			$data = $query->row;
			
			$data['keyword'] = '';

			$data['status'] = '0';
						
			$data = array_merge($data, array('news_description' => $this->getNewsDescriptions($news_id,1)));
			$data = array_merge($data, array('news_ncategory' => $this->getNewsNcategories($news_id)));
			$data = array_merge($data, array('news_layout' => $this->getNewsLayouts($news_id)));
			$data = array_merge($data, array('news_store' => $this->getNewsStores($news_id)));
			$data = array_merge($data, array('news_group' => $this->getNewsGroups($news_id)));
			$data = array_merge($data, array('news_related' => $this->getNewsRelated($news_id)));
			$data = array_merge($data, array('news_gallery' => $this->getArticleImages($news_id)));
			$data = array_merge($data, array('news_video' => $this->getArticleVideos($news_id)));
			
			$this->addNews($data,1);
		}
	}
	public function deleteNews($news_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_news WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_news_description WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'news_id=" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_news_to_ncategory WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_news_to_layout WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_news_to_store WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_news_to_group WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_news_related WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_news_gallery WHERE news_id = '" . (int)$news_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_news_video WHERE news_id = '" . (int)$news_id . "'");
		$this->cache->delete('news');
	}	
	public function getNewsStory($news_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'news_id=" . (int)$news_id . "') AS keyword FROM " . DB_PREFIX . "sb_news n LEFT JOIN " . DB_PREFIX . "sb_news_description nd ON (n.news_id = nd.news_id) WHERE n.news_id = '" . (int)$news_id . "' AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getNewsDescriptions($news_id, $ifcopy = 0) {
		$news_description_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sb_news_description WHERE news_id = '" . (int)$news_id . "'");
		foreach ($query->rows as $result) {
			if ($ifcopy) { $result['title'] = $result['title'] . ' copy'; }
			$news_description_data[$result['language_id']] = array(
				'title'       => $result['title'],
				'ctitle'      => $result['ctitle'],
				'ntags'       => $result['ntags'],
				'description' => $result['description'],
				'description2' => $result['description2'],
				'meta_desc'   => $result['meta_desc'],
				'meta_key'    => $result['meta_key'],
				'cfield1'     => $result['cfield1'],
				'cfield2'     => $result['cfield2'],
				'cfield3'     => $result['cfield3'],
				'cfield4'     => $result['cfield4'],
			);
		}
		return $news_description_data;
	}
    public function getNewsStores($news_id) {
		$news_store_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sb_news_to_store WHERE news_id = '" . (int)$news_id . "'");

		foreach ($query->rows as $result) {
			$news_store_data[] = $result['store_id'];
		}
		
		return $news_store_data;
	}

    public function getNewsGroups($news_id) {
		$news_group_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sb_news_to_group WHERE news_id = '" . (int)$news_id . "'");

		foreach ($query->rows as $result) {
			$news_group_data[] = $result['group_id'];
		}
		
		return $news_group_data;
	}

	public function getNewsLayouts($news_id) {
		$news_layout_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sb_news_to_layout WHERE news_id = '" . (int)$news_id . "'");
		
		foreach ($query->rows as $result) {
			$news_layout_data[$result['store_id']] = $result['layout_id'];
		}
		
		return $news_layout_data;
	}
	public function getNewsRelated($news_id) {
		$news_related_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sb_news_related WHERE news_id = '" . (int)$news_id . "'");
		
		foreach ($query->rows as $result) {
			$news_related_data[] = $result['product_id'];
		}
		
		return $news_related_data;
	}	
	public function getNewsNrelated($news_id) {
		$news_related_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sb_news_related WHERE news_id = '389657" . (int)$news_id . "'");
		$query2 = $this->db->query("SELECT * FROM " . DB_PREFIX . "sb_news_related WHERE product_id = '" . (int)$news_id . "' AND news_id LIKE '389657%'");
		
		foreach ($query->rows as $result) {
			$news_related_data[] = $result['product_id'];
		}
		foreach ($query2->rows as $result2) {
		 if (!in_array(str_replace("389657", "", $result2['news_id']), $news_related_data)) {
			$news_related_data[] = str_replace("389657", "", $result2['news_id']);
		 }
		}
		
		return $news_related_data;
	}	
	public function getNewsNcategories($news_id) {
		$news_category_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sb_news_to_ncategory WHERE news_id = '" . (int)$news_id . "'");
		
		foreach ($query->rows as $result) {
			$news_category_data[] = $result['ncategory_id'];
		}

		return $news_category_data;
	}
	
	public function getNews() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sb_news n LEFT JOIN " . DB_PREFIX . "sb_news_description nd ON (n.news_id = nd.news_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY n.date_added");
	
		return $query->rows;
	}
	
	public function getNewsLimited($data) {
		$sql = "SELECT * FROM " . DB_PREFIX . "sb_news n LEFT JOIN " . DB_PREFIX . "sb_news_description nd ON (n.news_id = nd.news_id) LEFT JOIN " . DB_PREFIX . "sb_nauthor na ON (n.nauthor_id = na.nauthor_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		if (!empty($data['filter_name'])) {
			$sql .= " AND LOWER(nd.title) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		if (isset($data['filter_status']) && $data['filter_status'] !== null) {
			$sql .= " AND n.status = '" . (int)$data['filter_status'] . "'";
		}
	
		$sort_data = array(
			'nd.title',
			'n.sort_order',
			'n.date_added',
			'na.name'
		);	
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
		} else {
			$sql .= " ORDER BY n.date_added";	
		}
		
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC";
		} else {
				$sql .= " ASC";
		}
		
		if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				

				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	
			
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getTotalNewsByAuthorId($nauthor_id) {
     	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "sb_news WHERE nauthor_id = '" . (int)$nauthor_id . "'");
		return $query->row['total'];
	}
	
	public function getAuthorIdbyArticle($news_id) {
     	$query = $this->db->query("SELECT nauthor_id FROM " . DB_PREFIX . "sb_news WHERE news_id = '" . (int)$news_id . "'");
		return $query->row['nauthor_id'];
	}
	
	public function getTotalNews($data = array()) {
		$sql = "SELECT COUNT(DISTINCT n.news_id) AS total FROM " . DB_PREFIX . "sb_news n LEFT JOIN " . DB_PREFIX . "sb_news_description nd ON (n.news_id = nd.news_id)";
		
		$sql .= " WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		if (!empty($data['filter_name'])) {
			$sql .= " AND LOWER(nd.title) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}
		if (isset($data['filter_status']) && $data['filter_status'] !== null) {
			$sql .= " AND n.status = '" . (int)$data['filter_status'] . "'";
		}
		$query = $this->db->query($sql);
		return $query->row['total'];
	}	
	
	public function getArticleImages($news_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sb_news_gallery WHERE news_id = '" . (int)$news_id . "'");

		return $query->rows;
	}
	
	public function getArticleVideos($news_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "sb_news_video WHERE news_id = '" . (int)$news_id . "'");

		return $query->rows;
	}
	
	public function addArchiveYear($data, $store=0) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "sb_news_archive SET year = '" . (int)$data['year'] . "', months = '" . $this->db->escape(serialize($data['months'])) . "', store_id = '" . (int)$store . "'");
	}
	
	public function deleteArchive() {
		$this->db->query("DELETE FROM " . DB_PREFIX . "sb_news_archive");
		$this->cache->delete('news');
	}
	
	public function getNewsDA() {

		$group_restriction = $this->config->get('ncategory_bnews_restrictgroup') ? " group_id," : '';

		$group_restriction_join = $this->config->get('ncategory_bnews_restrictgroup') ? "  LEFT JOIN " . DB_PREFIX . "sb_news_to_group n2g ON (n.news_id = n2g.news_id) " : '';

		$query = $this->db->query("SELECT date_added, store_id,".$group_restriction." n.news_id FROM " . DB_PREFIX . "sb_news n LEFT JOIN " . DB_PREFIX . "sb_news_to_store n2s ON (n.news_id = n2s.news_id)".$group_restriction_join." LEFT JOIN " . DB_PREFIX . "sb_news_description nd ON (n.news_id = nd.news_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n.status = '1' ORDER BY n.date_added");
	
		return $query->rows;
	}
	
	public function getNewsDU() {

		$group_restriction = $this->config->get('ncategory_bnews_restrictgroup') ? " group_id," : '';

		$group_restriction_join = $this->config->get('ncategory_bnews_restrictgroup') ? "  LEFT JOIN " . DB_PREFIX . "sb_news_to_group n2g ON (n.news_id = n2g.news_id) " : '';

		$query = $this->db->query("SELECT date_updated, store_id,".$group_restriction." n.news_id FROM " . DB_PREFIX . "sb_news n LEFT JOIN " . DB_PREFIX . "sb_news_to_store n2s ON (n.news_id = n2s.news_id)".$group_restriction_join." LEFT JOIN " . DB_PREFIX . "sb_news_description nd ON (n.news_id = nd.news_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n.status = '1' ORDER BY n.date_updated");
	
		return $query->rows;
	}
	
	public function getNewsDP() {

		$group_restriction = $this->config->get('ncategory_bnews_restrictgroup') ? " group_id," : '';

		$group_restriction_join = $this->config->get('ncategory_bnews_restrictgroup') ? "  LEFT JOIN " . DB_PREFIX . "sb_news_to_group n2g ON (n.news_id = n2g.news_id) " : '';

		$query = $this->db->query("SELECT date_pub, store_id,".$group_restriction." n.news_id FROM " . DB_PREFIX . "sb_news n LEFT JOIN " . DB_PREFIX . "sb_news_to_store n2s ON (n.news_id = n2s.news_id)".$group_restriction_join." LEFT JOIN " . DB_PREFIX . "sb_news_description nd ON (n.news_id = nd.news_id) WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND n.status = '1' ORDER BY n.date_pub");
	
		return $query->rows;
	}
}
?>
