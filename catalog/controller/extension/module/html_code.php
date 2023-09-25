<?php  
require_once modification( DIR_SYSTEM . 'library/html_code_mobile.php' );

class ControllerExtensionModuleHtmlCode extends Controller {
	
	protected $data = array();
	
	public function index( $settings ) {

	$status = Mobile_Detect_HTMLCODE::create()->isMobile();
	if( $settings['status'] == 'pc' && $status == true){
		return;
	}else if($settings['status'] == 'mobile' && $status != true){
		return;
	}

		if( ! in_array($this->config->get('config_store_id'), $settings['store_id'])){
			return;
		}	

		if( isset( $settings['layout_id'] ) && is_array( $settings['layout_id'] ) ) {
			if( in_array( $this->config->get('html_code_layout_i'), $settings['layout_id'] ) && isset( $this->request->get['information_id'] ) ) {
				if( ! empty( $settings['information_id'] ) && ! in_array( $this->request->get['information_id'], $settings['information_id'] ) )
					return;
			}

			if( in_array( $this->config->get('html_code_layout_c'), $settings['layout_id'] ) && isset( $this->request->get['path'] ) ) {
				if( ! empty( $settings['category_id'] ) ) {
					$has		= false;
					$categories	= explode( '_', $this->request->get['path'] );
					$categories = array( end( $categories ) );

					foreach( $categories as $category_id ) {
						if( in_array( $category_id, $settings['category_id'] ) ) {
							$has = true;
							break;
						}
					}

					if( ! $has )
						return;
				}
			}
			
			if( in_array( $this->config->get('html_code_layout_p')?$this->config->get('html_code_layout_p'):'2', $settings['layout_id'] ) && isset( $this->request->get['product_id'] ) ) {
				if( ! empty( $settings['product_id'] ) ) {
					if( ! in_array( $this->request->get['product_id'], $settings['product_id'] ) )
						return;
				}
			}
		}
		
		if( isset( $settings['store_id'] ) && is_array( $settings['store_id'] ) && ! in_array( $this->config->get('config_store_id'), $settings['store_id'] ) )
			return;
		
		if( ! empty( $settings['customer_group_id'] ) ) {
			if( $this->customer->isLogged() ) {
				$customer_group_id = $this->customer->getGroupId();
			} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}
			
			if( ! in_array( $customer_group_id, $settings['customer_group_id'] ) )
				return;
		}
		
		$this->data['description'] = isset( $settings['description'][$this->config->get('config_language_id')] ) ? $settings['description'][$this->config->get('config_language_id')] : '';
		
		if( $this->data['description'] === '' ) {
			return;
		}
		
		$this->data['header'] = isset( $settings['header'][$this->config->get('config_language_id')] ) ? $settings['header'][$this->config->get('config_language_id')] : '';
		$this->data['mode']	= isset( $settings['mode'] ) ? $settings['mode'] : 'none';
		
		$this->data['description'] = htmlspecialchars_decode( $this->data['description'] );
		
		if( ! empty( $settings['php'] ) ) {
			$md5 = isset( $settings['md5'][$this->config->get('config_language_id')] ) ? $settings['md5'][$this->config->get('config_language_id')] : md5( $this->data['description'] );
			$file = DIR_CACHE . 'html_code_tmp.' . $this->config->get('config_language_id') . '.' . $md5 . '.php';
			$time = DIR_CACHE . 'html_code_tmp.' . $this->config->get('config_language_id') . '.' . $md5 . '.time';
			$created = file_exists( $time ) ? file_get_contents( $time ) : 0;
			
			if( $created && isset( $settings['time'] ) && $created < $settings['time'] ) {
				$created = 0;
				
				if( file_exists( $file ) ) {
					unlink( $file );
				}
				
				if( file_exists( $time ) ) {
					unlink( $time );
				}
			}
			
			if( ! $created || ! file_exists( $file ) ) {
				$created = time();
				
				file_put_contents( $file, $this->data['description'] );
				file_put_contents( $time, $created );
			}
			
			ob_start();
			include $file;
			$this->data['description'] = ob_get_clean();
		}
		
		$this->document->addStyle('catalog/view/theme/default/stylesheet/html-code/style.css');
		
		return ($this->load->view('extension/module/html_code', $this->data));
	}
}
?>