<?php
class ControllerExtensionModulegallery extends Controller {
	public function index($setting) {
		static $module = 0;

		$this->load->language('module/gallery');
		
		$this->load->model('extension/gallery');
		
		$this->load->model('tool/image');
		
		
		$data['heading_title'] = $setting['name'];
		
		$this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/owl.carousel.css');
		$this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/owl.transitions.css');
		$this->document->addScript('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js');
		
		$data['total_views']= $this->config->get('gallerysetting_album_views');
		$data['caption_text_clr']= $this->config->get('gallerysetting_caption_text_clr');
		$data['caption_bg_color']= $this->config->get('gallerysetting_caption_bg_clr');
		
		$data['albums'] = array();

		if (!empty($setting['album'])) {
		 $results = array_slice($setting['album'], 0, (int)$setting['limit']);
		}else{
		  $results = array();
		}
		
		foreach ($results as $album_id) {
			$album_info  = $this->model_extension_gallery->getAlbumDesc($album_id);
			if($album_info) {
				$data['albums'][] = array(
					'title' => $album_info['title'],
					'description' => utf8_substr(strip_tags(html_entity_decode($album_info['description'], ENT_QUOTES, 'UTF-8')), 0 , $this->config->get('gallerysetting_album_description_length')) . '...',
					'image' => $this->model_tool_image->resize($album_info['image'], $setting['width'], $setting['height']),
					'href'    => $this->url->link('extension/photo', '&album_id=' . $album_info['album_id'] ),
					'viewed' =>  $this->model_extension_gallery->TotalViewed($album_info['album_id']),
				);
			}
		}

		$data['module'] = $module++;
		
		return $this->load->view('extension/module/gallery', $data);
	}
}