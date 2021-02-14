<?php
class ControllerExtensiongallerysetting extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/gallerysetting');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extension/album');
		
		$this->model_extension_album->createtable();
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			//$this->model_extension_album->sethomeblogurl($this->request->post['keyword']);
			
			$this->model_setting_setting->editSetting('gallerysetting', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->response->redirect($this->url->link('extension/gallerysetting', 'user_token=' . $this->session->data['user_token'], true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');
		
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		
		$data['entry_popupwidth'] = $this->language->get('entry_popupwidth');
		$data['entry_popupheight'] = $this->language->get('entry_popupheight');
		
		$data['entry_album_height'] = $this->language->get('entry_album_height');
		$data['entry_album_width'] = $this->language->get('entry_album_width');
		
		$data['entry_photo_height'] = $this->language->get('entry_photo_height');
		$data['entry_photo_width'] = $this->language->get('entry_photo_width');
		$data['entry_headermenu'] = $this->language->get('entry_headermenu');
		
		$data['entry_popup_height'] = $this->language->get('entry_popup_height');
		$data['entry_popup_width'] = $this->language->get('entry_popup_width');
		$data['entry_album_description_length'] = $this->language->get('entry_album_description_length');
		$data['help_album_description_length'] = $this->language->get('help_album_description_length');
		$data['entry_photo_limit'] = $this->language->get('entry_photo_limit');
		$data['entry_album_views'] = $this->language->get('entry_album_views');
		$data['entry_caption_bg_clr'] = $this->language->get('entry_caption_bg_clr');
		$data['entry_caption_text_clr'] = $this->language->get('entry_caption_text_clr');
		$data['entry_headermenu_name'] = $this->language->get('entry_headermenu_name');
		$data['entry_headermenu_sort_order'] = $this->language->get('entry_headermenu_sort_order');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['help_keyword'] = $this->language->get('help_keyword');

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['album_description_length'])) {
			$data['error_album_description_length'] = $this->error['album_description_length'];
		} else {
			$data['error_album_description_length'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/gallerysetting', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/gallerysetting', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('extension/album', 'user_token=' . $this->session->data['user_token'], true);
		
		$url='';
		$data['add'] = $this->url->link('extension/album/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['webx_album_list'] = $this->url->link('extension/album', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['webx_setting'] = $this->url->link('extension/gallerysetting', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->post['gallerysetting_status'])) {
			$data['gallerysetting_status'] = $this->request->post['gallerysetting_status'];
		} else {
			$data['gallerysetting_status'] = $this->config->get('gallerysetting_status');
		}
	
		if (isset($this->request->post['gallerysetting_height'])) {
			$data['gallerysetting_height'] = $this->request->post['gallerysetting_height'];
		} else {
			$data['gallerysetting_height'] = $this->config->get('gallerysetting_height');
		}
		
		if (isset($this->request->post['gallerysetting_width'])) {
			$data['gallerysetting_width'] = $this->request->post['gallerysetting_width'];
		} else {
			$data['gallerysetting_width'] = $this->config->get('gallerysetting_width');
		}
		
		if (isset($this->request->post['gallerysetting_albumheight'])) {
			$data['gallerysetting_albumheight'] = $this->request->post['gallerysetting_albumheight'];
		} else {
			$data['gallerysetting_albumheight'] = $this->config->get('gallerysetting_albumheight');
		}
		
		if (isset($this->request->post['gallerysetting_albumwidth'])) {
			$data['gallerysetting_albumwidth'] = $this->request->post['gallerysetting_albumwidth'];
		} else {
			$data['gallerysetting_albumwidth'] = $this->config->get('gallerysetting_albumwidth');
		}
		
		if (isset($this->request->post['gallerysetting_popupheight'])) {
			$data['gallerysetting_popupheight'] = $this->request->post['gallerysetting_popupheight'];
		} else {
			$data['gallerysetting_popupheight'] = $this->config->get('gallerysetting_popupheight');
		}
		
		if (isset($this->request->post['gallerysetting_popupwidth'])) {
			$data['gallerysetting_popupwidth'] = $this->request->post['gallerysetting_popupwidth'];
		} else {
			$data['gallerysetting_popupwidth'] = $this->config->get('gallerysetting_popupwidth');
		}
		
		
		if (isset($this->request->post['gallerysetting_album_views'])) {
			$data['gallerysetting_album_views'] = $this->request->post['gallerysetting_album_views'];
		} else {
			$data['gallerysetting_album_views'] = $this->config->get('gallerysetting_album_views');
		}
		
		
		if (isset($this->request->post['gallerysetting_caption_bg_clr'])) {
			$data['gallerysetting_caption_bg_clr'] = $this->request->post['gallerysetting_caption_bg_clr'];
		} else {
			$data['gallerysetting_caption_bg_clr'] = $this->config->get('gallerysetting_caption_bg_clr');
		}
		
		if (isset($this->request->post['gallerysetting_caption_text_clr'])) {
			$data['gallerysetting_caption_text_clr'] = $this->request->post['gallerysetting_caption_text_clr'];
		} else {
			$data['gallerysetting_caption_text_clr'] = $this->config->get('gallerysetting_caption_text_clr');
		}
		
		if (isset($this->request->post['gallerysetting_headermenu'])) {
			$data['gallerysetting_headermenu'] = $this->request->post['gallerysetting_headermenu'];
		} else {
			$data['gallerysetting_headermenu'] = $this->config->get('gallerysetting_headermenu');
		}
		
		if (isset($this->request->post['gallerysetting_menu_sort_order'])) {
			$data['gallerysetting_menu_sort_order'] = $this->request->post['gallerysetting_menu_sort_order'];
		} else {
			$data['gallerysetting_menu_sort_order'] = $this->config->get('gallerysetting_menu_sort_order');
		}

		
		if (isset($this->request->post['gallerysetting_photo_limit'])) {
			$data['gallerysetting_photo_limit'] = $this->request->post['gallerysetting_photo_limit'];
		} else {
			$data['gallerysetting_photo_limit'] = $this->config->get('gallerysetting_photo_limit');
		}
		
			$this->load->model('setting/store');

		$data['stores'] = array();
		
		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		);
		
		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = array(
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			);
		}
		
		//print_r($data['gallery_seo_url']); die();
		
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		foreach ($data['languages'] as $language) {
			
			if (isset($this->request->post['gallerysetting_menu' . $language['language_id']])) {
				$data['gallerysetting_menu'][$language['language_id']] = $this->request->post['gallerysetting_menu' . $language['language_id']];
			} elseif ($this->config->get('gallerysetting_menu' . $language['language_id'])){
				$data['gallerysetting_menu'][$language['language_id']] = $this->config->get('gallerysetting_menu' . $language['language_id']);
			}else {
				$data['gallerysetting_menu'][$language['language_id']] = '';
			}
		}
		
	
		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/gallerysetting', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/gallerysetting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}