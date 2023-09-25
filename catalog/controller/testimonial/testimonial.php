<?php
class ControllerTestimonialTestimonial extends Controller{

    private $error = array();
    private $moduleName 			= 'testimonial';
    private $moduleModel 			= 'model_extension_module_testimonial';
    private $moduleModelPath 		= 'extension/module/testimonial';
    private $modulePath 		    = 'testimonial/testimonial';
    private $moduleVersion 			= '1.4.2';

    public function index(){

        $lang_ar = $this->load->language($this->moduleModelPath);

        foreach($lang_ar as $key => $item){
            $data[$key] = $item;
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $this->document->setTitle($data['heading_title']);

        $this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
        $this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
        
        $data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));

        $this->load->model($this->moduleModelPath);

        $data['review_status'] = $this->config->get('config_review_status');

        if ($this->config->get('config_review_guest') || $this->customer->isLogged()) {
            $data['review_guest'] = true;
        } else {
            $data['review_guest'] = false;
        }

        if ($this->customer->isLogged()) {
            $data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
        } else {
            $data['customer_name'] = '';
        }

        $data['breadcrumbs'][] = array(
            'text' => $data['heading_title'],
            'href' => $this->url->link($this->modulePath)
        );

        $data['review'] = 'index.php?route=' . $this->modulePath . '/review';
        $data['write'] = 'index.php?route=' . $this->modulePath . '/write';

        if(substr(VERSION, 0, 7) > '2.1.0.1'){
            if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('testimonial', (array)$this->config->get('config_captcha_page'))) {
                $data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
            } else {
                $data['captcha'] = '';
            }
        }else{
            if ($this->config->get('config_google_captcha_status')) {
                $this->document->addScript('https://www.google.com/recaptcha/api.js');
                $data['site_key'] = $this->config->get('config_google_captcha_public');
            } else {
                $data['site_key'] = '';
            }
        }

        $page = 1;

        if(isset($this->request->get['page'])){
            $page = (int)$this->request->get['page'];
        }

        $theme = $this->config->get('config_theme');

        $limit = $this->config->get( $theme . '_testimonial_limit');

        $start = ($page - 1) * $limit;

        $total = $this->model_extension_module_testimonial->getTotalReviews();
        
        $results = $this->model_extension_module_testimonial->getReviews($start, $limit);

        $data['testimonials'] = array();

        foreach ($results as $result) { 
            $data['testimonials'][] = array(
                'review_id'     => $result['review_id'],
                'description'   =>  html_entity_decode($result['text'], ENT_QUOTES, 'UTF-8'),
                'rating'        => (int)$result['rating'],
                'author'        => $result['author'],
                'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
            );
        }

        $page_data = array(
            'total' =>	$total,
            'page'	=>	$page,
            'limit'	=>	$limit,
            'url'	=>	$this->url->link('testimonial/testimonial', 'page={page}'),
        );

        $data = array_merge($this->load->controller('component/pagination', $page_data), $data);

        $data = $this->load->controller('component/common', $data);

        $this->response->setOutput($this->load->view('testimonial/testimonial', $data));
    }

    public function review() {
        $this->load->language($this->moduleModelPath);
        $this->load->model($this->moduleModelPath);

        $data['text_no_reviews'] = $this->language->get('text_no_reviews');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['reviews'] = array();

        $review_total = $this->{$this->moduleModel}->getTotalReviews();

        $results = $this->{$this->moduleModel}->getReviews(($page - 1) * 5, 5);

        foreach ($results as $result) {
            $data['reviews'][] = array(
                'author'     => $result['author'],
                'text'       => nl2br($result['text']),
                'rating'     => (int)$result['rating'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
            );
        }

        $pagination = new Pagination();

        $pagination->total = $review_total;
        $pagination->page = $page;
        $pagination->limit = 5;
        $pagination->url = 'index.php?route=' . $this->modulePath . '/review&page={page}';

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($review_total - 5)) ? $review_total : ((($page - 1) * 5) + 5), $review_total, ceil($review_total / 5));

        if(substr(VERSION, 0, 7) > '2.1.0.2'){
            $this->response->setOutput($this->load->view($this->modulePath . '_list', $data));
        }else{
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $this->modulePath . '_list.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/' . $this->modulePath . '_list.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/' . $this->modulePath . '_list.tpl', $data));
            }
        }
    }

    public function write() {
        
        $this->load->language($this->moduleModelPath);

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            
            if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
                $json['error'] = $this->language->get('error_name');
            }

            if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 3000)) {
                $json['error'] = $this->language->get('error_text');
            }
            if (empty($this->request->post['rating']) || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
                $json['error'] = $this->language->get('error_rating');
            }

            if ($this->config->get('config_google_captcha_status')) {
                $recaptcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($this->config->get('config_google_captcha_secret')) . '&response=' . $this->request->post['g-recaptcha-response'] . '&remoteip=' . $this->request->server['REMOTE_ADDR']);

                $recaptcha = json_decode($recaptcha, true);

                if (!$recaptcha['success']) {
                    $json['error'] = $this->language->get('error_captcha');
                }
            }

            if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('testimonial', (array)$this->config->get('config_captcha_page'))) {
                $captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

                if ($captcha) {
                    $json['error'] = $captcha;
                }
            }

            if (!isset($json['error'])) {
                $this->load->model($this->moduleModelPath);
                $json['success'] = $this->language->get('text_success');

                $this->{$this->moduleModel}->addReview($this->request->post);
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}