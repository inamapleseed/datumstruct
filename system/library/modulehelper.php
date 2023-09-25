<?php
class Modulehelper {

  private $Module_helper;
  private static $instance;
  private $registry;

  public static function get_instance($registry) {
    if (is_null(static::$instance)) {
      static::$instance = new static($registry);
    }

    return static::$instance;
  }

  public function init ( $array ) {
    // required variables
    $oc = $array['oc']; // The "this" from controller
    $heading_title = isset($array['heading_title']) ? $array['heading_title'] : 'Custom Module';
    $modulename = isset($array['modulename']) ? $array['modulename'] : 'custommodule';
    $single_ckeditor = isset($array['single_ckeditor']) ? $array['single_ckeditor'] : false;
    $multi_ckeditor = isset($array['multi_ckeditor']) ? $array['multi_ckeditor'] : false;
    $fields = isset($array['fields']) ? $array['fields'] : array();
    $oc->document->setTitle($heading_title);

    $data['alerts'] = array();
    if(isset($array['alerts']) && is_array($array['alerts']) && $array['alerts']){
      $data['alerts'] = $array['alerts'];
    }

    $oc->load->model('setting/setting');
    //$moduleprefix = 'module_';
    $moduleprefix = '';

    if (($oc->request->server['REQUEST_METHOD'] == 'POST') && $this->validate($oc, $modulename)) {
       // Start If: Validates and check if data is coming by save (POST) method

       $new_posts = array();
        foreach($oc->request->post as $k => $p) {
         // if is repeater then rearrange the index of its array 
         if(is_array($p)) {
            $id = 0;
            foreach($p as $k2 => $p2) {
              $new_posts[$k][$id] = $p2;
              $id++;
            }
         }
         else {
          $new_posts[$k] = $p;
         }
       }

       //$oc->model_setting_setting->editSetting($moduleprefix . $modulename, $oc->request->post);  
       $oc->model_setting_setting->editSetting($moduleprefix . $modulename, $new_posts);      // Parse all the coming data to Setting Model to save it in database.


       $oc->session->data['success'] = 'Success: You have modified module ' . $heading_title . '!'; // To display the success text on data save

        // $oc->response->redirect($oc->url->link('marketplace/extension', 'token=' . $oc->session->data['token'], 'SSL'));
        // $oc->response->redirect($oc->url->link('marketplace/extension', 'token=' . $oc->session->data['token'] . '&type=module', true)); // Redirect to the Module Listing
       $oc->response->redirect($oc->url->link('extension/module/' . $modulename, 'token=' . $oc->session->data['token'], 'SSL'));
   } // End If

   /*Assign the language data for parsing it to view*/
   $data['heading_title'] = $heading_title;
   $data['modulename'] = $modulename;

   $data['text_enabled'] = 'Enabled';
   $data['text_disabled'] = 'Disabled';
   $data['text_content_top'] = 'Content Top';
   $data['text_content_bottom'] = 'Content Bottom';
   $data['text_column_left'] = 'Column Left';
   $data['text_column_right'] = 'Column Right';

   $data['entry_code'] = 'Code';
   $data['entry_layout'] = 'Layout';
   $data['entry_position'] = 'Position';
   $data['entry_status'] = 'Status';
   $data['entry_sort_order'] = 'Sort Order';

   $data['button_save'] = 'Save';
   $data['button_cancel'] = 'Cancel';
   $data['button_add_module'] = 'Add Module';
   $data['button_remove'] = 'Remove';


   /*This Block returns the warning if any*/
   if (isset($this->error['warning'])) {
     $this->data['error_warning'] = $this->error['warning'];
   } else {
     $this->data['error_warning'] = '';
   }
   /*End Block*/

   /*This Block returns the error code if any*/
   if (isset($oc->error['code'])) {
     $data['error_code'] = $oc->error['code'];
   } else {
     $data['error_code'] = '';
   }
   /*End Block*/


   /* Making of Breadcrumbs to be displayed on site*/
   $data['breadcrumbs'] = array();

   $data['breadcrumbs'][] = array(
     'text'      => 'Home',
     'href'      => $oc->url->link('common/dashboard', 'token=' . $oc->session->data['token'], 'SSL'),
     'separator' => false
   );

   $data['breadcrumbs'][] = array(
     'text'      => 'Modules',
     'href'      => $oc->url->link('extension/extension', 'token=' . $oc->session->data['token'] . '&type=module', 'SSL'),
     'separator' => ' :: '
   );

   $data['breadcrumbs'][] = array(
     'text'      => $heading_title,
     'href'      => $oc->url->link('extension/module/' . $modulename, 'token=' . $oc->session->data['token'], 'SSL'),
     'separator' => ' :: '
   );

   /* End Breadcrumb Block*/

   $data['action'] = $oc->url->link('extension/module/' . $modulename, 'token=' . $oc->session->data['token'], 'SSL'); // URL to be directed when the save button is pressed

   $data['cancel'] = $oc->url->link('extension/extension', 'token=' . $oc->session->data['token'] . '&type=module', 'SSL'); // URL to be redirected when cancel button is pressed


   /* This block checks, if the hello world text field is set it parses it to view otherwise get the default hello world text field from the database and parse it*/

   // wk code here

   if (isset($oc->session->data['success'])) {
    $data['success'] = $oc->session->data['success'];
    unset($oc->session->data['success']);
  }
  $oc->load->model('localisation/language');
  $data['languages'] = $oc->model_localisation_language->getLanguages();
  $oc->load->model('tool/image');

  $ctr = 1;
  foreach ($fields as $field):

   $this->get_field_value( $oc, $field, $moduleprefix, $modulename, $data );

   $ctr ++;
 endforeach;
 /* End Block*/

 if (isset($oc->request->post[$moduleprefix . $modulename.'_status'])) {
   $data[$moduleprefix . $modulename.'_status'] = $oc->request->post[$moduleprefix . $modulename.'_status'];
 } else {
   $data[$moduleprefix . $modulename.'_status'] = $oc->config->get($moduleprefix . $modulename.'_status');
 }
 /* End Block*/

   // default image
   $data['default_image'] = $oc->model_tool_image->resize('no_image.png', 100, 100);

   $oc->load->model('design/layout'); // Loading the Design Layout Models

   // $oc->template = 'module/module_helper.php'; // Loading the
   $data['header'] = $oc->load->controller('common/header');
   $data['column_left'] = $oc->load->controller('common/column_left');
   $data['footer'] = $oc->load->controller('common/footer');
   $data['fields'] = $fields;

   // for ckeditor use
   $data["base_url"] = HTTPS_CATALOG;
   $data['token'] = $oc->session->data['token'];
   $data['ckeditor_skin'] = 'moono-lisa';
   $data['codemirror_skin'] = 'base16-dark';

   $oc->response->setOutput($oc->load->view('extension/module/module_helper', $data)); // Rendering the Output
 }

 public function __construct ( $registry ) {

  $this->registry = $registry;

  } // end of construct

  public function get_field ( $oc, $modulename, $language_id, $name, $type = false ) {
    // $moduleprefix = 'module_';
    $moduleprefix = '';
    $var = $oc->config->get($moduleprefix . $modulename . '_' . $name . '_' . $language_id);

    if ( $type ) {
      $oc->load->model('tool/image');

      if (isset($oc->request->server['HTTPS']) && (($oc->request->server['HTTPS'] == 'on') || ($oc->request->server['HTTPS'] == '1'))) {
       $base = $oc->config->get('config_ssl');
     } else {
       $base = $oc->config->get('config_url');
     }

     if($type == 'image') {

      return $base . 'image/' . $var;

    }
    if(is_array($type)) {
      for ( $i = 0; $i < count($var); $i ++ ):
        foreach ( $type as $item ) {
          if(isset($var[$i][$item]) && $var[$i][$item] != ''):
            $var[$i][$item . '_thumb'] =  $oc->model_tool_image->resize($var[$i][$item], 100, 100);
            $var[$i][$item]            =  $base . 'image/' . $var[$i][$item];
          endif;
          } // endforeach
        endfor;
      } // end of is_array
    } // end if type
    return $var;
  }

  protected function get_field_value ( $oc, $field, $moduleprefix, $modulename, &$data ) {

    $type = isset($field['type']) ? $field['type'] : 'text';
    $label = isset($field['label']) ? $field['label'] : 'Label ' . $ctr;
    $name = isset($field['name']) ? $field['name'] : 'name' . $ctr;

    foreach ( $data['languages'] as $language ) :
      if ( $type == 'text' || $type == 'number' || $type == 'image' || $type == 'dropdown' || $type == 'textarea'|| $type == 'repeater' ) :

        if (isset($oc->request->post[$moduleprefix . $modulename . '_' . $name . '_' . $language['language_id']])) {
          $data[$moduleprefix . $modulename . '_' . $name . '_' . $language['language_id']] = $oc->request->post[$moduleprefix . $modulename . '_' . $name . '_' . $language['language_id']];
        } else {
          $data[$moduleprefix . $modulename . '_' . $name . '_' . $language['language_id']] = $oc->config->get($moduleprefix . $modulename . '_' . $name . '_' . $language['language_id']);
        }

      endif;

      if ( $type == 'image' ):

        if (isset($this->request->post[$moduleprefix . $modulename . '_' . $name . '_' . $language['language_id']]) && is_file(DIR_IMAGE . $this->request->post[$moduleprefix . $modulename . '_' . $name . '_' . $language['language_id']])) {
          $data[ $moduleprefix . $modulename . '_' . $name . '_' . $language['language_id'] . '_thumb' ] = $oc->model_tool_image->resize($this->request->post[$moduleprefix . $modulename . '_' . $name . '_' . $language['language_id'] . '_thumb'], 100, 100);
        } elseif ( is_file(DIR_IMAGE . $oc->config->get($moduleprefix . $modulename . '_' . $name . '_' . $language['language_id'])) ) {
          $data[ $moduleprefix . $modulename . '_' . $name . '_' . $language['language_id'] . '_thumb' ] = $oc->model_tool_image->resize( $oc->config->get($moduleprefix . $modulename . '_' . $name . '_' . $language['language_id']), 100, 100 );
        } else {
          $data[ $moduleprefix . $modulename . '_' . $name . '_' . $language['language_id'] . '_thumb' ] = $oc->model_tool_image->resize('no_image.png', 100, 100);
        }

      endif;


      if ( $type == 'repeater' ):
        $rows = array();
        $data[$modulename . 'ctr'] = 0;
        $rows = $data[$moduleprefix . $modulename . '_' . $name . '_' . $language['language_id']];
        if($rows){
          for ($i=0; $i < count($rows); $i++):
            foreach ( $field['fields'] as $item ) {
              if ( $item['type'] == 'image' ):

                if (isset($rows[$i][$item['name']]) && is_file(DIR_IMAGE . $rows[$i][$item['name']])) {
                  $data[$moduleprefix . $modulename . '_' . $name . '_' . $language['language_id']][$i][$item['name'] . '_thumb']  = $oc->model_tool_image->resize( $rows[$i][$item['name']] , 100, 100 );
                } else {
                  $data[$moduleprefix . $modulename . '_' . $name . '_' . $language['language_id']][$i][$item['name'] . '_thumb']  = $oc->model_tool_image->resize('no_image.png', 100, 100);
                }

              endif;
            } // end foreach
          endfor;
          // print_r ( $data[$moduleprefix . $modulename . '_' . $name . '_' . $language['language_id']] );
        }

      endif;

  endforeach;

}

protected function validate($oc, $modulename) {
 if (!$oc->user->hasPermission('modify', 'extension/module/' . $modulename)) {
   $oc->error['warning'] = 'You do not have permission to view this module.';
 }

 return !$oc->error;
}

}
// end of class
