<?php
class ModelExtensionalbum extends Model {
	public function createtable(){
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "album` (`album_id` int(11) NOT NULL AUTO_INCREMENT,`bottom` int(1) NOT NULL DEFAULT '0',`sort_order` int(3) NOT NULL DEFAULT '0',`status` tinyint(1) NOT NULL DEFAULT '1',`image` varchar(255) NOT NULL,`sort_order0` int(11) NOT NULL,PRIMARY KEY (`album_id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "albumimg` (`albumimg_id` int(11) NOT NULL AUTO_INCREMENT,`album_id` int(11) NOT NULL,`sort_order` int(3) NOT NULL DEFAULT '0',`image` varchar(255) NOT NULL,PRIMARY KEY (`albumimg_id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=141");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "albumimg_description` (`albumimg_id` int(11) NOT NULL,`album_id` int(11) NOT NULL,`language_id` int(11) NOT NULL,`title` varchar(255) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "album_description` (`album_id` int(11) NOT NULL,`language_id` int(11) NOT NULL,`title` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,`description` text CHARACTER SET big5 COLLATE big5_bin NOT NULL,PRIMARY KEY (`album_id`,`language_id`),FULLTEXT KEY `title` (`title`),FULLTEXT KEY `description` (`description`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");
	}
	
	public function sethomeblogurl($keyword){
		$this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'extension/album'");
		if(isset($keyword)){
			$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'extension/album', keyword = '" . $this->db->escape($keyword) . "'");
		}
	}
	
	public function gethomeblogurl($path){
	  $query = $this->db->query("SELECT keyword FROM ".DB_PREFIX."seo_url WHERE query = '".$this->db->escape($path)."'")->row;
	  if(!empty($query['keyword'])){
		  return $query['keyword'];
	  }else{
		  return '';
	  }
	}
	
	public function addalbum($data) {
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "album SET sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', image = '" . $this->db->escape($data['image']) . "'");
		
		$album_id = $this->db->getLastId();
		
		if (isset($data['album_description'])) {
			foreach ($data['album_description'] as $language_id => $value) {	
				$this->db->query("INSERT INTO " . DB_PREFIX . "album_description SET album_id = '" . (int)$album_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
			}
		}
		
		if (isset($data['album_image'])) {
			foreach ($data['album_image'] as $album_image) {
				
				$this->db->query("INSERT INTO " . DB_PREFIX . "albumimg SET album_id = '" . (int)$album_id . "', image = '" .  $this->db->escape($album_image['image']) . "', sort_order = '". (int)$album_image['sort_order'] ."'");

				$albumimg_id = $this->db->getLastId();

				foreach ($album_image['album_image_description'] as $language_id => $album_image_description) {
		
					$this->db->query("INSERT INTO " . DB_PREFIX . "albumimg_description SET albumimg_id = '" . (int)$albumimg_id . "',  album_id = '" . (int)$album_id . "', language_id = '" . (int)$language_id . "', title = '" .  $this->db->escape($album_image_description['title']) . "'");
				}
			}
		}
		
		if (isset($data['gallery_seo_url'])) {
			foreach ($data['gallery_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!empty($keyword)) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'album_id=" . (int)$album_id . "', keyword = '" . $this->db->escape($keyword) . "'");
					}
				}
			}
		}
		
		return $album_id;
	}

	public function editalbum($album_id, $data) {
	
		
		$this->db->query("UPDATE " . DB_PREFIX . "album SET sort_order = '" . (int)$data['sort_order'] . "', image = '" . $this->db->escape($data['image']) . "', status = '" . (int)$data['status'] . "' WHERE album_id = '" . (int)$album_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "album_description WHERE album_id = '" . (int)$album_id . "'");

		foreach ($data['album_description'] as $language_id => $value) {
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "album_description SET album_id = '" . (int)$album_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "'");
			
		}
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "albumimg WHERE album_id = '" . (int)$album_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "albumimg_description WHERE album_id = '" . (int)$album_id . "'");
		
		if (isset($data['album_image'])) {
			foreach ($data['album_image'] as $album_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "albumimg SET album_id = '" . (int)$album_id . "', image = '" .  $this->db->escape($album_image['image']) . "', sort_order = '". (int)$album_image['sort_order'] ."'");

				$albumimg_id = $this->db->getLastId();

				foreach ($album_image['album_image_description'] as $language_id => $album_image_description) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "albumimg_description SET albumimg_id = '" . (int)$albumimg_id . "',  album_id = '" . (int)$album_id . "', language_id = '" . (int)$language_id . "', title = '" .  $this->db->escape($album_image_description['title']) . "'");
				}
			}
		}
		
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE query = 'album_id=" . (int)$album_id . "'");

		if (isset($data['gallery_seo_url'])) {
			foreach ($data['gallery_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!empty($keyword)) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'album_id=" . (int)$album_id . "', keyword = '" . $this->db->escape($keyword) . "'");
					}
				}
			}
		}
	}

	public function deletealbum($album_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "album WHERE album_id = '" . (int)$album_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "album_description WHERE album_id = '" . (int)$album_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "albumimg WHERE album_id = '" . (int)$album_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "albumimg_description WHERE album_id = '" . (int)$album_id . "'");
		
	}

	public function getalbum($album_id) {
		$query = $this->db->query("SELECT DISTINCT *,(SELECT DISTINCT keyword FROM " . DB_PREFIX . "seo_url WHERE query = 'album_id=" . (int)$album_id . "') AS keyword FROM " . DB_PREFIX . "album WHERE album_id = '" . (int)$album_id . "'");

		return $query->row;
	}
	
	public function getalbumdesc($album_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "album_description WHERE album_id = '" . (int)$album_id . "'");

		return $query->row;
	}

		public function getalbums($data = array()) {
		
			$sql = "SELECT * FROM " . DB_PREFIX . "album i LEFT JOIN " . DB_PREFIX . "album_description id ON (i.album_id = id.album_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "'";

			if (!empty($data['filter_title'])) {
				$sql .= " AND id.title LIKE '" . $this->db->escape($data['filter_title']) . "%'";
			}
			
			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$sql .= " AND i.status = '" . (int)$data['filter_status'] . "'";
			}


			$sort_data = array(
				'id.title',
				'i.sort_order'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY id.title";
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

	public function getalbumDescriptions($album_id) {
		$album_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "album_description WHERE album_id = '" . (int)$album_id . "'");

		foreach ($query->rows as $result) {
			$album_description_data[$result['language_id']] = array(
				'title'            => $result['title'],
				'description'      => $result['description'],
			);
		}

		return $album_description_data;
	}
	
	//photos
	public function getalbumimages($album_id) {
			$albumimg_data = array();

			$albumimg_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "albumimg WHERE album_id = '" . (int)$album_id . "' ORDER BY sort_order ASC");

			foreach ($albumimg_query->rows as $albumimg) {
				$albumimg_description_data = array();

				$albumimg_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "albumimg_description WHERE albumimg_id = '" . (int)$albumimg['albumimg_id'] . "' AND album_id = '" . (int)$album_id . "'");

				foreach ($albumimg_description_query->rows as $albumimg_description) {
					$albumimg_description_data[$albumimg_description['language_id']] = array('title' => $albumimg_description['title']);
				}

				$albumimg_data[] = array(
					'albumimg_image_description'     => $albumimg_description_data,
					'image'                    => $albumimg['image'],
					'sort_order'               => $albumimg['sort_order']
				);
			}

			return $albumimg_data;
		}
	//end photos	
	
	public function getGallerySeoUrls($album_id) {
		$gallery_seo_url_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'album_id=" . (int)$album_id . "'");

		foreach ($query->rows as $result) {
			$gallery_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $gallery_seo_url_data;
	}

	public function getTotalalbums($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "album i LEFT JOIN " . DB_PREFIX . "album_description id ON (i.album_id = id.album_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		
			if (!empty($data['filter_title'])) {
				$sql .= " AND id.title LIKE '" . $this->db->escape($data['filter_title']) . "%'";
			}
			
			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				$sql .= " AND i.status = '" . (int)$data['filter_status'] . "'";
			}
			
			$query = $this->db->query($sql);
		return $query->row['total'];
	}
	
	public function getTotalphotos($album_id) {
		$sql = "SELECT COUNT(*) AS total1 FROM " . DB_PREFIX . "albumimg where album_id='". (int)$album_id ."'";
		$query = $this->db->query($sql);
		
		return $query->row['total1'];
	}	
}