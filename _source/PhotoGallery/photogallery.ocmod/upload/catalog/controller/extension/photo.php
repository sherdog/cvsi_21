<?php
class ControllerExtensionPhoto extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('extension/photo');
			
		$this->load->model('extension/gallery');
		
		$this->model_extension_gallery->createtable();
		
		$this->load->model('tool/image');		
		
		if (isset($this->request->get['album_id'])) {
			$album_id = (int)$this->request->get['album_id'];
		} else {
			$album_id = 0;
		}
		
		$this->model_extension_gallery->updateViewed($album_id);
		$data['viewed'] =  $this->model_extension_gallery->TotalViewed($album_id);
		$album_data = $this->model_extension_gallery->getAlbumDesc($album_id);
		
		if ($album_data) {
			$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');

			
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);
			
			$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_albums'),
					'href' => $this->url->link('extension/album')
			);
		
			$data['breadcrumbs'][] = array(
				'text' => $album_data['title'],
				'href' => $this->url->link('extension/photo', 'album_id=' .  $album_id)
			);
			
			$this->document->setTitle($album_data['title']);

			$data['heading_title'] = $album_data['title'];

			$data['text_no_results'] = $this->language->get('text_no_results');
			
			$data['caption_text_clr']= $this->config->get('gallerysetting_caption_text_clr');
			
			$data['caption_bg_color']= $this->config->get('gallerysetting_caption_bg_clr');
			
			$data['button_continue'] = $this->language->get('button_continue');

			$data['description'] = html_entity_decode($album_data['description'], ENT_QUOTES, 'UTF-8');

			$data['continue'] = $this->url->link('common/home');
			
			$data['share'] = $this->url->link('extension/photo','&album_id='.$album_id);

			$myresults = $this->model_extension_gallery->getAlbumDesc($this->request->get['album_id']);
		
			$data['albums'][] = array(
				'title'   =>  $myresults['title'],
				'description'   => html_entity_decode($myresults['description'], ENT_QUOTES, 'UTF-8')
			);
			
			if(isset($this->request->get['page'])){
				$page = $this->request->get['page'];
			}else{
				$page = 0;
			}
			
			
			$data['album_id'] = $this->request->get['album_id'];
			
			
			
			$data['page'] = $page;
			$filterdata=array(
			  'album_id'  => $this->request->get['album_id'],
			  'start'     => $page,
			  'limit'	  => $this->config->get('gallerysetting_photo_limit')
			);
			
			$results = $this->model_extension_gallery->getPhotos($filterdata);
			
			
			$data['photos']=array();
			foreach ($results as $result) {
				if ($result['image']) {
					$popup = $this->model_tool_image->resize($result['image'],
					$this->config->get('gallerysetting_popupwidth')!=""?$this->config->get('gallerysetting_popupwidth'):300,
					$this->config->get('gallerysetting_popupheight')!=""?$this->config->get('gallerysetting_popupheight'):300);
				} else {
					$popup = '';
				}
				
				$data['photos'][] = array(
					'image'   => $this->model_tool_image->resize($result['image'],
					$this->config->get('gallerysetting_height')!=""?$this->config->get('gallerysetting_height'):200,
					
					$this->config->get('gallerysetting_width')!=""?$this->config->get('gallerysetting_width'):200),
					'title'   => $result['title'],
					'popup'   => $popup,
				);
			}
			
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			
			$this->response->setOutput($this->load->view('extension/photo', $data));
		}else{
			$this->response->redirect($this->url->link('extension/album'));
		}
	}
	
	public function getmore(){
		$this->load->language('extension/photo');
			
		$this->load->model('extension/gallery');
		
		$this->load->model('tool/image');
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		
		if(isset($this->request->get['page'])){
				$page = $this->request->get['page'];
			}else{
				$page = 1;
			}
		
			
			$data['album_id'] = $this->request->get['album_id'];
			
				if (isset($this->request->get['album_id'])) {
				$album_id = (int)$this->request->get['album_id'];
				} else {
					$album_id = 0;
				}
		
			$this->model_extension_gallery->updateViewed($album_id);
			$data['viewed'] =  $this->model_extension_gallery->TotalViewed($album_id);
			$album_data = $this->model_extension_gallery->getAlbumDesc($album_id);
			
			$data['page'] = $page;
			if(!$this->config->get('gallerysetting_photo_limit')){
			 $this->config->set('gallerysetting_photo_limit',10); 
			}
			$filterdata=array(
			  'album_id'  => $this->request->get['album_id'],
			  'start'     => ($page-1) * $this->config->get('gallerysetting_photo_limit'),
			  'limit'	  => $this->config->get('gallerysetting_photo_limit')
			);
			
			
			$results = $this->model_extension_gallery->getPhotos($filterdata);
			
			
			$data['photos']=array();
			foreach ($results as $result) {
				if ($result['image']) {
					$popup = $this->model_tool_image->resize($result['image'],
					$this->config->get('gallerysetting_popupwidth')!=""?$this->config->get('gallerysetting_popupwidth'):300,
					$this->config->get('gallerysetting_popupheight')!=""?$this->config->get('gallerysetting_popupheight'):300);
				} else {
					$popup = '';
				}
				
				$data['photos'][] = array(
					'image'   => $this->model_tool_image->resize($result['image'],
					$this->config->get('gallerysetting_height')!=""?$this->config->get('gallerysetting_height'):200,
					
					$this->config->get('gallerysetting_width')!=""?$this->config->get('gallerysetting_width'):200),
					'title'   => $result['title'],
					'popup'   => $popup,
				);
			}
		
		$this->response->setOutput($this->load->view('extension/photo_ajax', $data));
	}
}