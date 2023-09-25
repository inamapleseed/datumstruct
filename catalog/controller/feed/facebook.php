<?php
//Created by Patrick Verloo (Antalus).
//Only to be used with a valid license.
//E-mail to webmaster@antalus.nl for more information or to purchase a license.
class ControllerFeedFacebook extends Controller {

    private function clean($data) {
        $data = str_ireplace(PHP_EOL, "", $data);
        $data = strip_tags(html_entity_decode($data));
        $data = str_ireplace('"', "", $data);
        return preg_replace("/\s+/", " ", $data);
    }

    public function index() {
        if ($this->config->get('facebook_csv_status') == 1) {
            if (!empty($this->config->get('facebook_csv_secret'))) {
                if (!isset($_GET['secret'])) {
                    echo "You do not have access to this Facebook CSV feed";
                    exit;
                } else {
                    $secretcompare = $_GET['secret'];
                    $secret = $this->config->get('facebook_csv_secret');
                }

                if ($secret != $secretcompare) {
                    echo "You do not have access to this Facebook CSV feed";
                    exit;
                }
            }

            $this->load->model('catalog/product');
            $this->load->model('tool/image');

            $products = $this->model_catalog_product->getProducts();

            $file = fopen($this->config->get('config_name') . '-faceboook' . $secret . '.csv', 'w');
            fputcsv($file, array('id','title','description','price','link','image_link','availability','brand','condition'));
            $csv_file = array();

            foreach ($products as $product) {				if ($product['status'] == 1) {				
					if (!empty($product['image'])) {
						$name = str_replace(array('®', '™'), array('', ''), $product['name']);

						$csv_file[1] = $product['product_id'];
						$csv_file[2] = $this->clean($name);
						if (!empty($product['description'])) {
							$csv_file[3] = $this->clean($product['description']);
						} else {
							$csv_file[3] = $this->clean($name);
						}
						$csv_file[4] = round($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), 2) . ' ' . $this->session->data['currency'];
						$csv_file[5] = str_ireplace("&amp;", "&", $this->url->link('product/product', 'product_id=' . $product['product_id']));
						$csv_file[6] = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height'));
						$csv_file[7] = ($product['quantity'] > 0 ? 'in stock' : 'out of stock');
						$csv_file[8] = str_replace(array('®', '™'), array('', ''), $product['manufacturer']);
						$csv_file[9] = "new";

						fputcsv($file, $csv_file);
					}								}
            }

            fclose($file);
            
            echo "The Facebook CSV feed has been created and can be found on this URL (This is also the URL you need at Facebook Ads): " . HTTP_SERVER . $this->config->get('config_name') . '-faceboook' . $secret . '.csv';
        } else {
            echo "The Facebook CSV feed is not active. Go to Extensions->Feeds->Facebook CSV feed in the admin to activate the module.";
            exit;
        }
    }
}
?>