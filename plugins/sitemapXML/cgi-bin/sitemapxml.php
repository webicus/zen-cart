<?php
/**
 * Sitemap XML Feed
 *
 * @package Sitemap XML Feed
 * @copyright Copyright 2005-2010, Andrew Berezin eCommerce-Service.com
 * @copyright Portions Copyright 2003-2008 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @link http://www.sitemaps.org/
 * @version $Id: sitemapxml.php, v 2.1.0 30.04.2009 10:35 AndrewBerezin $
 */
// php -f /home/XXXXXXX/domains/XXXX.ru/public_html/cgi-bin/sitemapxml.php rebuild=yes ping=yes
// /usr/local/bin/php -f /home/XXXXXXX/data/www/XXXX.ru/cgi-bin/sitemapxml.php rebuild=yes ping=yes

if (!is_file(dirname(__FILE__) . '/includes/configure.php')) {
  chdir(dirname(__FILE__) . '/../');
} else {
  chdir(dirname(__FILE__));
}

//echo '<pre>';var_dump($_SERVER);echo '</pre>';
//echo '<pre>';var_dump($_GET);echo '</pre>';
if ((!isset($_GET) || sizeof($_GET) == 0) && isset($_SERVER["argc"]) && sizeof($_SERVER["argv"]) > 1) {
  for($i=1,$n=sizeof($_SERVER["argv"]); $i<$n; $i++) {
    list($key, $val) = explode('=', $_SERVER["argv"][$i]);
    $_GET[$key] = $_REQUEST[$key] = $val;
  }
}

if (!isset($_SERVER['REQUEST_URI'])) $_SERVER['REQUEST_URI'] = '/' . basename($_SERVER['SCRIPT_NAME']);
if (!isset($_SERVER['REMOTE_ADDR'])) $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
if (!isset($_SERVER['HTTP_USER_AGENT'])) $_SERVER['REMOTE_ADDR'] = 'Cron /usr/local/bin/php -f ';

$_GET['main_page'] = 'sitemapxml';

function zen_sitemapxml_callback($html) {
  $html = str_replace("&nbsp;", " ", $html);
  $html = preg_replace("@\s\s+@", " ", $html);
  $html = preg_replace('@<head>(.*)</'.'head>@si', '', $html);
  $html = preg_replace('@<script(.*)</'.'script>@si', '', $html);
  $html = preg_replace('@<title>(.*)</'.'title>@si', '', $html);
  $html = preg_replace('@(</h[1-4]>)@si', "$1\n", $html);
  $html = preg_replace('@(<h[1-4]>|<div)@si', "\n$1", $html);
  $html = preg_replace('@(<br\s*[/]*>|<p.*>|</p>|</div>|</h\d+>)@si', "$1\n", $html);
  $html = preg_replace("@\n +@", "\n", $html);
  $html = strip_tags($html);
  $html = trim($html);
  return $html;
}
ob_start("zen_sitemapxml_callback");

include('index.php');