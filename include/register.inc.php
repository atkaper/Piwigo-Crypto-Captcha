<?php
if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

include(CRYPTO_PATH.'include/common.inc.php');
add_event_handler('loc_end_page_header', 'add_crypto');
add_event_handler('register_user_check', 'check_crypto');

function add_crypto()
{
  global $template;
  $template->set_prefilter('register', 'prefilter_crypto');
}

function prefilter_crypto($content, $smarty)
{
  $search = '#\(\{\'useful when password forgotten\'\|@translate\}\)(\s*)((\{/if\})?)#i';
  $replace = '({\'useful when password forgotten\'|@translate})$1$2'."\n".'{\$CRYPTOGRAPHP}';
  return preg_replace($search, $replace, $content);
}

function check_crypto($errors)
{
  include_once(CRYPTO_PATH.'securimage/securimage.php');
  $securimage = new Securimage();
  
  if ($securimage->check($_POST['captcha_code']) == false)
  {
    array_push($errors, l10n('Invalid Captcha'));
  }

  return $errors;
}

?>