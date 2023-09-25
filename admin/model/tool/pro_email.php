<?php
define('PRO_EMAIL_ADMIN', true);
if (defined('JPATH_MIJOSHOP_OC')) {
  if(file_exists(JPATH_MIJOSHOP_OC.'/system/modification/catalog/model/tool/pro_email.php')) {
    require_once(JPATH_MIJOSHOP_OC.'/system/modification/catalog/model/tool/pro_email.php');
  } else {
    require_once(JPATH_MIJOSHOP_OC.'/catalog/model/tool/pro_email.php');
  }
} else if (isset($vqmod)) {
  if (function_exists('modification')) {
    require_once($vqmod->modCheck(modification(DIR_SYSTEM.'../catalog/model/tool/pro_email.php')));
  } else {
    require_once($vqmod->modCheck(DIR_SYSTEM.'../catalog/model/tool/pro_email.php'));
  }
} else if (class_exists('VQMod')) {
  if (function_exists('modification')) {
    require_once(VQMod::modCheck(modification(DIR_SYSTEM.'../catalog/model/tool/pro_email.php')));
  } else {
    require_once(VQMod::modCheck(DIR_SYSTEM.'../catalog/model/tool/pro_email.php'));
  }
} else {
  if (function_exists('modification')) {
    require_once(modification(DIR_SYSTEM.'../catalog/model/tool/pro_email.php'));
  } else {
    require_once(DIR_SYSTEM.'../catalog/model/tool/pro_email.php');
  }
}