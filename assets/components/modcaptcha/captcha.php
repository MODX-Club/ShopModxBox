<?php

ini_set('display_errors', 1);

define('MODX_API_MODE', true);

// initialize $modx
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/index.php';

define('CAPTCHA_SESSION_ID', $modx->getOption('modcaptcha.session_id', null, 'php_captcha'));

// include captcha class
require MODX_CORE_PATH.'components/modcaptcha/php-captcha.inc.php';

$fonts = array('fonts/VeraBd.ttf', 'fonts/VeraIt.ttf', 'fonts/Vera.ttf');
$width = $modx->getOption('modcaptcha.width', null, 150);
$height = $modx->getOption('modcaptcha.height', null, 38);
$chars = $modx->getOption('modcaptcha.chars', null, 'a-z,A-Z,1-9');
$captcha = new PhpCaptcha($fonts, $width, $height);
$captcha->SetCharSet($chars);
$captcha->SetNumChars($modx->getOption('modcaptcha.num_chars', null, 5));
$captcha->SetNumLines($modx->getOption('modcaptcha.num_lines', null, 2));
$captcha->Create();