<?php
class ModelExtensionGallery extends Model {
	
	public function updateViewed($album_id) {
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "album` LIKE 'viewed'");
        if(!$query->num_rows){
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "album` ADD `viewed` int(11) NOT NULL AFTER `image`");
        }
		$this->db->query("UPDATE " . DB_PREFIX . "album SET viewed = (viewed + 1) WHERE album_id = '" . (int)$album_id . "'");
	}
	
	public function TotalViewed($album_id) {
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "album` LIKE 'viewed'");
        if(!$query->num_rows){
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "album` ADD `viewed` int(11) NOT NULL AFTER `image`");
        }
	 return $this->db->query("SELECT * FROM " . DB_PREFIX . "album  WHERE album_id = '" . (int)$album_id . "'")->row['viewed'];
	}
	
	public function createtable(){
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "album` (`album_id` int(11) NOT NULL AUTO_INCREMENT,`bottom` int(1) NOT NULL DEFAULT '0',`sort_order` int(3) NOT NULL DEFAULT '0',`status` tinyint(1) NOT NULL DEFAULT '1',`image` varchar(255) NOT NULL,`sort_order0` int(11) NOT NULL,PRIMARY KEY (`album_id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "albumimg` (`albumimg_id` int(11) NOT NULL AUTO_INCREMENT,`album_id` int(11) NOT NULL,`sort_order` int(3) NOT NULL DEFAULT '0',`image` varchar(255) NOT NULL,PRIMARY KEY (`albumimg_id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=141");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "albumimg_description` (`albumimg_id` int(11) NOT NULL,`album_id` int(11) NOT NULL,`language_id` int(11) NOT NULL,`title` varchar(255) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "album_description` (`album_id` int(11) NOT NULL,`language_id` int(11) NOT NULL,`title` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,`description` text CHARACTER SET big5 COLLATE big5_bin NOT NULL,PRIMARY KEY (`album_id`,`language_id`),FULLTEXT KEY `title` (`title`),FULLTEXT KEY `description` (`description`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");
		
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "album` LIKE 'viewed'");
        if(!$query->num_rows){
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "album` ADD `viewed` int(11) NOT NULL AFTER `image`");
        }
	}
	
	public function getAlbums($data = array()) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "album i LEFT JOIN " . DB_PREFIX . "album_description id on (i.album_id=id.album_id) WHERE language_id = '". $this->config->get('config_language_id') ."' AND i.status = '1' ORDER BY i.sort_order ASC, id.title");

			$album_data = $query->rows;

		return $album_data;
	}
	
	public function getAlbumDesc($album_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "album i LEFT JOIN " . DB_PREFIX . "album_description id ON (i.album_id = id.album_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' AND i.album_id = '". (int)$album_id ."'";

		$query = $this->db->query($sql);
		
		return $query->row;
	}	
	
	public function getPhotos($data) {
		$sql ="SELECT * FROM " . DB_PREFIX . "album a LEFT JOIN " . DB_PREFIX . "albumimg ai on(ai.album_id = a.album_id ) LEFT JOIN " . DB_PREFIX . "albumimg_description aid on(aid.albumimg_id=ai.albumimg_id) WHERE aid.language_id = '" . (int)$this->config->get('config_language_id') . "' AND ai.album_id='". (int)$data['album_id'] ."' AND aid.album_id='". (int)$data['album_id'] ."' ORDER BY ai.sort_order ASC ";
		
		
	
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
		
		$photo_data = $query->rows;

		return $photo_data;
	}
	
	
}