<?php
class ControllerExtensionAlbum extends Controller {
	private $error = array();

	public function index() {
		
		$this->load->language('extension/album');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/album');
		
		$this->model_extension_album->createtable();
		
		$this->getList();
	}

	public function add() {
		$this->load->language('extension/album');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/album');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_extension_album->addalbum($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/album', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('extension/album');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extension/album');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			$this->model_extension_album->editalbum($this->request->get['album_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/album', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('extension/album');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/album');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $album_id) {
				$this->model_extension_album->deletealbum($album_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/album', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		
		if (isset($this->request->get['filter_title'])) {
			$filter_title = $this->request->get['filter_title'];
		} else {
			$filter_title = null;
		}
		
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'id.title';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		
		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/album', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('extension/album/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['webx_album_list'] = $this->url->link('extension/album', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['webx_setting'] = $this->url->link('extension/gallerysetting', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('extension/album/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);
		
		
		$data['albums'] = array();
		
		$filter_data = array(
			'filter_title'  => $filter_title,
			'filter_status'  => $filter_status,
			'sort'  => $sort,
			
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);
		
		$album_total = $this->model_extension_album->getTotalalbums($filter_data);
		
		$results = $this->model_extension_album->getalbums($filter_data);
		
		
		
		foreach ($results as $result) {

			
			$data['albums'][] = array(
			
				'album_id' => $result['album_id'],
				'title'          => $result['title'],
				'status'          => ($result['status']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'total1'=> $this->model_extension_album->getTotalphotos($result['album_id']),	
				'sort_order'     => $result['sort_order'],
				'edit'           => $this->url->link('extension/album/edit', 'user_token=' . $this->session->data['user_token'] . '&album_id=' . $result['album_id'] . $url, true)
			);
		}
		
		
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_enabled'] = $this->language->get('text_enable');
		$data['text_disabled'] = $this->language->get('text_disable');
		$data['entry_image_amount'] = $this->language->get('entry_image_amount');
		$data['column_title'] = $this->language->get('column_title');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');
		$data['column_status'] = $this->language->get('column_status');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}
		
		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		

		$data['sort_title'] = $this->url->link('extension/album', 'user_token=' . $this->session->data['user_token'] . '&sort=id.title' . $url, true);
		$data['sort_status'] = $this->url->link('extension/album', 'user_token=' . $this->session->data['user_token'] . '&sort=i.status' . $url, true);
		$data['sort_sort_order'] = $this->url->link('extension/album', 'user_token=' . $this->session->data['user_token'] . '&sort=i.sort_order' . $url, true);

		$url = '';
		
		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $album_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/album', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($album_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($album_total - $this->config->get('config_limit_admin'))) ? $album_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $album_total, ceil($album_total / $this->config->get('config_limit_admin')));
		
		$data['user_token'] = $this->session->data['user_token'];
		
		$data['filter_title'] = $filter_title;
		$data['filter_status'] = $filter_status;
		
		$data['sort'] = $sort;
		$data['order'] = $order;
		
		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/album_list', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['album_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_photo_title'] = $this->language->get('entry_photo_title');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['column_action'] = $this->language->get('column_action');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_album_add'] = $this->language->get('button_album_add');
		$data['button_remove'] = $this->language->get('button_remove');
		
		
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_photos'] = $this->language->get('tab_photos');
		
		$data['help_keyword'] = $this->language->get('help_keyword');
		
		
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = array();
		}
		
		If (isset($this->error['image'])) {
			$data['error_image'] = $this->error['image'];
		} else {
			$data['error_image'] = array();
		}

		if (isset($this->error['description'])) {
			$data['error_description'] = $this->error['description'];
		} else {
			$data['error_description'] = array();
		}

		if (isset($this->error['album_image'])) {
			$data['error_album_image'] = $this->error['album_image'];
		} else {
			$data['error_album_image'] = array();
		}

		if (isset($this->error['album_images'])) {
			$data['error_album_images'] = $this->error['album_images'];
		} else {
			$data['error_album_images'] = array();
		}
		
		
		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['add'] = $this->url->link('extension/album/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['webx_album_list'] = $this->url->link('extension/album', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['webx_setting'] = $this->url->link('extension/gallerysetting', 'user_token=' . $this->session->data['user_token'] . $url, true);
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/album', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);
		
		
		if (!isset($this->request->get['album_id'])) {
			$data['action'] = $this->url->link('extension/album/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('extension/album/edit', 'user_token=' . $this->session->data['user_token'] . '&album_id=' . $this->request->get['album_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('extension/album', 'user_token=' . $this->session->data['user_token'] . $url, true);
		
		if (isset($this->request->get['album_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$album_info = $this->model_extension_album->getalbum($this->request->get['album_id']);
		}
		
		$data['user_token'] = $this->session->data['user_token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['album_description'])) {
			$data['album_description'] = $this->request->post['album_description'];
		} elseif (isset($this->request->get['album_id'])) {
			$data['album_description'] = $this->model_extension_album->getalbumDescriptions($this->request->get['album_id']);
		} else {
			$data['album_description'] = array();
		}
		
		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($album_info)) {
			$data['image'] = $album_info['image'];
		} else {
			$data['image'] = '';
		}
		
		if (isset($this->request->post['keyword'])) {
			$data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($album_info)) {
			$data['keyword'] = $album_info['keyword'];
		} else {
			$data['keyword'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($album_info) && is_file(DIR_IMAGE . $album_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($album_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($album_info)) {
			$data['status'] = $album_info['status'];
		} else {
			$data['status'] = true;
		}
		
		if (isset($this->request->post['gallery_seo_url'])) {
			$data['gallery_seo_url'] = $this->request->post['gallery_seo_url'];
		} elseif (isset($this->request->get['album_id'])) {
			$data['gallery_seo_url'] = $this->model_extension_album->getGallerySeoUrls($this->request->get['album_id']);
		} else {
			$data['gallery_seo_url'] = array();
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($album_info)) {
			$data['sort_order'] = $album_info['sort_order'];
		} else {
			$data['sort_order'] = '';
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
		
		//photos
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->load->model('tool/image');

		if (isset($this->request->post['album_image'])) {
			
			$album_images = $this->request->post['album_image'];
		} elseif (isset($this->request->get['album_id'])) {
			$album_images = $this->model_extension_album->getalbumimages($this->request->get['album_id']);
		} else {
			$album_images = array();
		}

		$data['album_images'] = array();

		foreach ($album_images as $album_image) {
			if (is_file(DIR_IMAGE . $album_image['image'])) {
				$image = $album_image['image'];
				$thumb = $album_image['image'];
			} else {
				$image = '';
				$thumb = 'no_image.png';
			}

			$data['album_images'][] = array(
				
				'album_image_description' =>  isset($album_image['albumimg_image_description']) ? $album_image['albumimg_image_description'] : array(), 
				'image'                    => $image,
				'thumb'                    => $this->model_tool_image->resize($thumb, 100, 100),
				'sort_order'               => $album_image['sort_order']
			);
			
			//print_r($album_images); die();
		}
		//end photos
		
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/album_form', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/album')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		foreach ($this->request->post['album_description'] as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 255)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		}
		
		if (isset($this->request->post['album_image'])) {
			foreach ($this->request->post['album_image'] as $album_image_id => $album_image) {
				if (empty($album_image['image'])) {
						$this->error['album_images'][$album_image_id] = $this->language->get('error_photo_image');
					}
				
				foreach ($album_image['album_image_description'] as $language_id => $album_image_description) {
					if ((utf8_strlen($album_image_description['title']) < 3) || (utf8_strlen($album_image_description['title']) > 255)) {
						//$this->error['album_image'][$album_image_id][$language_id] = $this->language->get('error_photo_title');
					}
					
				}
			}
		}
		
		if ($this->request->post['gallery_seo_url']) {
			$this->load->model('design/seo_url');
			
			foreach ($this->request->post['gallery_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!empty($keyword)) {
						if (count(array_keys($language, $keyword)) > 1) {
							$this->error['keyword'][$store_id][$language_id] = $this->language->get('error_unique');
						}

						$seo_urls = $this->model_design_seo_url->getSeoUrlsByKeyword($keyword);
	
						foreach ($seo_urls as $seo_url) {
							if (($seo_url['store_id'] == $store_id) && (!isset($this->request->get['album_id']) || ($seo_url['query'] != 'album_id=' . $this->request->get['album_id']))) {		
								$this->error['keyword'][$store_id][$language_id] = $this->language->get('error_keyword');
				
								break;
							}
						}
					}
				}
			}
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		 if (!$this->user->hasPermission('modify', 'extension/album')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}


		return !$this->error;
	}
	
	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_title'])) {
			$this->load->model('extension/album');
			
			if (isset($this->request->get['filter_title'])) {
				$filter_title = $this->request->get['filter_title'];
			} else {
				$filter_title = '';
			}

			
			
			$filter_data = array(
				'filter_title' => $this->request->get['filter_title'],
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_extension_album->getalbums($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'album_id' => $result['album_id'],
					'name'            => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}