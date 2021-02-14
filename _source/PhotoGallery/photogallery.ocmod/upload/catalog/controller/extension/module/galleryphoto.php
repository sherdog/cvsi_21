<?php
class ControllerExtensionModulegalleryphoto extends Controller {
	public function index($setting) {
		static $module = 0;

		$this->load->model('extension/gallery');
		
		$this->load->model('tool/image');

		$this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/owl.carousel.css');
		$this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/owl.transitions.css');
		$this->document->addScript('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js');

		$data['photos'] = array();

		$filter_data=array(
		 'album_id' => $setting['album_id'],
		);
		$data['heading_title'] = $setting['name'];
		$data['caption_text_clr']= $this->config->get('gallerysetting_caption_text_clr');
		$data['caption_bg_color']= $this->config->get('gallerysetting_caption_bg_clr');
		
		$results = $this->model_extension_gallery->getPhotos($filter_data);

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				if ($result['image']) {
					$popup = $this->model_tool_image->resize($result['image'],
					$this->config->get('gallerysetting_popupwidth')!=""?$this->config->get('gallerysetting_popupwidth'):300,
					$this->config->get('gallerysetting_popupheight')!=""?$this->config->get('gallerysetting_popupheight'):300);
				} else {
					$popup = '';
				}
				
				$data['photos'][] = array(
					'title' 	=> $result['title'],
					'image' 	=> $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']),
					'popup'   => $popup,
				);
			}
		}
		
		if (!empty($setting['limit'])) {
			$data['limit'] = $setting['limit'];
		} else {
			$data['limit'] = 5;
		}

		$data['module'] = $module++;

		return $this->load->view('extension/module/galleryphoto', $data);
	}
}