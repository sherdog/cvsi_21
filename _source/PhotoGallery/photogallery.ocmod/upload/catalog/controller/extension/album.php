<?php
class ControllerExtensionAlbum extends Controller {
	public function index() {
		$this->load->language('extension/album');
		
		$this->load->model('extension/gallery');
		
		$this->model_extension_gallery->createtable();
		
		$this->load->model('tool/image');		
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		
		$this->document->setTitle($data['heading_title']);
		
		$data['breadcrumbs'][] = array(
				'text' => $data['heading_title'],
				'href' => $this->url->link('extension/album')
		);
		
		$data['total_views']= $this->config->get('gallerysetting_album_views');
		$data['caption_text_clr']= $this->config->get('gallerysetting_caption_text_clr');
		$data['caption_bg_color']= $this->config->get('gallerysetting_caption_bg_clr');
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		
		$results = $this->model_extension_gallery->getAlbums();
		
		$data['albums'] = array();
		
		foreach ($results as $result) {
			$data['albums'][] = array(
				'image'   => $this->model_tool_image->resize($result['image'],
				$this->config->get('gallerysetting_albumheight')!=""?$this->config->get('gallerysetting_albumheight'):200,
				$this->config->get('gallerysetting_albumwidth')!=""?$this->config->get('gallerysetting_albumwidth'):200),
				'status'  => $result['status'],
				'title'   => $result['title'],
				'viewed' =>  $this->model_extension_gallery->TotalViewed($result['album_id']),
				'href'    => $this->url->link('extension/photo', '&album_id='. $result['album_id'])
			);
		}
		
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
		$this->response->setOutput($this->load->view('extension/album', $data));
	}
}