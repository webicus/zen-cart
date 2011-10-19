<?php
/**
 * Sitemap XML
 *
 * @package Sitemap XML
 * @copyright Copyright 2005-2011, Andrew Berezin eCommerce-Service.com
 * @copyright Portions Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @link http://www.sitemaps.org/
 * @version $Id: sitemapxml.php, v 2.3.4 05.08.2011 13:43:08 AndrewBerezin $
 */
////////////////////////////////////////////////////////////////////////
// Sitemap Base Class
@define('SITEMAPXML_MAX_ENTRYS', 50000);
@define('SITEMAPXML_MAX_SIZE', 10000000); // 10485760

class zen_SiteMapXML {
  var $savepath;
  var $sitemap;
  var $videomap;
  var $sitemapindex;
  var $compress;
  var $base_url;
  var $magicSeo = false;
  var $submitFlag_url;
  var $duplicatedLinks;
  var $checkurl;
  var $languagesCodes = array();
  var $languagesIDsArray = array();
  var $languagesIDs;
  var $languagesCount = 0;
  var $default_language_id = 0;

  var $sitemapItems = array();
  var $submitFlag = true;
  var $inline = true;
  var $ping = false;
  var $rebuild = false;
  var $genxml = true;
  var $stylesheet = '';

  var $sitemapFileItems = 0;
  var $sitemapFileSize = 0;
  var $sitemapFileItemsTotal = 0;
  var $sitemapFileSizeTotal = 0;
  var $sitemapFileName;
  var $sitemapFileNameNumber = 0;
  var $sitemapFileFooter = '</urlset>';
  var $sitemapFileHeader;
  var $sitemapFileBuffer = '';
  var $sitemapxml_max_entrys;
  var $sitemapxml_max_size;
  var $timezone;

  var $fb_maxsize = 4096;
  var $fb = '';
  var $fp = null;
  var $fn = '';

  var $statisticTotalTime = 0;
  var $statisticTotalQueries = 0;
  var $statisticTotalQueriesTime = 0;
  var $statisticModuleTime = 0;
  var $statisticModuleQueries = 0;
  var $statisticModuleQueriesTime = 0;

  function zen_SiteMapXML($inline=false, $ping=false, $rebuild=false, $genxml=true) {
    global $db;
    $this->statisticTotalTime = explode (' ', microtime());
    $this->statisticTotalTime = $this->statisticTotalTime[1]+$this->statisticTotalTime[0];
    $this->statisticTotalQueries = $db->count_queries;
    $this->statisticTotalQueriesTime = $db->total_query_time;
    $this->statisticModuleTime = explode (' ', microtime());
    $this->statisticModuleTime = $this->statisticModuleTime[1]+$this->statisticModuleTime[0];
    $this->statisticModuleQueries = $db->count_queries;
    $this->statisticModuleQueriesTime = $db->total_query_time;

    $this->sitemap = 'sitemap';
    $this->videomap = 'videomap';
    $this->sitemapindex = SITEMAPXML_SITEMAPINDEX . '.xml';
    $this->compress = (SITEMAPXML_COMPRESS == 'true' ? true : false);
    $this->duplicatedLinks = array();
    $this->sitemapItems = array();
    $this->savepath = DIR_FS_CATALOG;
    $this->base_url = HTTP_SERVER . DIR_WS_CATALOG;
    $this->submit_url = utf8_encode(urlencode($this->base_url . $this->sitemapindex));
    $this->submitFlag = true;
    $this->inline = $inline;
    $this->ping = $ping;
    $this->rebuild = $rebuild;
    $this->checkurl = false;
    $this->genxml = $genxml;
    $this->sitemapFileFooter = '</urlset>';
    $this->sitemapFileBuffer = '';
    $this->sitemapxml_max_entrys = SITEMAPXML_MAX_ENTRYS;
    $this->sitemapxml_max_size = SITEMAPXML_MAX_SIZE-strlen($this->sitemapFileFooter);
    global $lng;
    if (!is_object($lng)) {
      $lng = new language();
    }
    foreach ($lng->catalog_languages as $language) {
      $this->languagesCodes[$language['id']] = $language['code'];
      $this->languagesDirectory[$language['id']] = $language['directory'];
      $this->languagesIDsArray[] = $language['id'];
      if ($language['code'] == DEFAULT_LANGUAGE) {
        $this->default_language_id = $language['id'];
      }
    }
    $this->languagesIDs = implode(',', $this->languagesIDsArray);
    $this->languagesCount = sizeof($this->languagesCodes);

    $this->sitemapItems = array();

    $timezone = date('O', $date);
    $this->timezone = substr($timezone, 0, 3) . ":" . substr($timezone, 3, 2);

    $this->magicSeo = false;
    if (function_exists('unMagicSeoDoSeo')) {
      $this->magicSeo = true;
    }

    if ($this->inline) {
      ob_start();
    }

//    echo 'Save path - "' . $this->savepath . '"' . '<br />';
/*
    if (!($robots_txt = @file_get_contents($this->savepath . 'robots.txt'))) {
      echo '<b>File "robots.txt" not found in save path - "' . $this->savepath . 'robots.txt"</b>' . '<br />';
    } elseif (!preg_match("@Sitemap:\s*(.*)\n@i", $robots_txt . "\n", $m)) {
      echo '<b>Sitemap location don\'t specify in robots.txt</b>' . '<br />';
    } elseif (trim($m[1]) != $this->base_url . $this->sitemapindex) {
      echo '<b>Sitemap location specified in robots.txt "' . trim($m[1]) . '" another than "' . $this->base_url . $this->sitemapindex . '"</b>' . '<br />';
    }
*/
  }

  function SitemapOpen($file, $last_date=0, $type='sitemap') {
    if (strlen($this->sitemapFileBuffer) > 0) $this->SitemapClose();
    if (!$this->genxml) return false;
    $this->sitemapFile = $file;
    $this->sitemapType = $type;
    $this->sitemapFileName = $this->_getNameFileXML($file);
    if ($this->_checkFTimeSitemap($this->sitemapFileName, $last_date) == false) return false;
    if (!$this->_fileOpen($this->sitemapFileName)) return false;
    $this->_SitemapReSet();
    $this->sitemapFileBuffer .= $this->_SitemapXMLHeader();
    return true;
  }

  function SitemapSetMaxItems($maxItems) {
    $this->sitemapFileItemsMax = $maxItems;
    return true;
  }

  function SitemapWriteItem($loc, $lastmod='', $changefreq='', $xtra='') {
    if (!$this->genxml) return false;
    if ($this->magicSeo) {
      $href = '<a href="' . $loc . '">';
      $out = unMagicSeoDoSeo($href);
      $loc = substr($out, 0, -2);
      $loc = substr($loc, 9);
    }
    if (isset($this->duplicatedLinks[$loc])) return false;
    $this->duplicatedLinks[$loc] = true;
    if ($this->checkurl) {
      if (!($info = $this->_curlExecute($loc, 'header')) || $info['http_code'] != 200) return false;
    }
    $itemRecord  = '';
    $itemRecord .= ' <url>' . "\n";
    $itemRecord .= '  <loc>' . utf8_encode($loc) . '</loc>' . "\n";
    if (isset($lastmod) && zen_not_null($lastmod) && (int)$lastmod > 0) {
      $itemRecord .= '  <lastmod>' . $this->_LastModFormat($lastmod) . '</lastmod>' . "\n";
    }
    if (zen_not_null($changefreq) && $changefreq != 'no') {
      $itemRecord .= '  <changefreq>' . $changefreq . '</changefreq>' . "\n";
    }
    if ($this->sitemapFileItemsMax > 0) {
      $itemRecord .= '  <priority>' . number_format(max((($this->sitemapFileItemsMax-$this->sitemapFileItemsTotal)/$this->sitemapFileItemsMax), 0.10), 2, '.', '') . '</priority>' . "\n";
    }
    if (isset($xtra) && zen_not_null($xtra)) {
      $itemRecord .= $xtra;
    }
    $itemRecord .= ' </url>' . "\n";

    if ($this->sitemapFileItems >= $this->sitemapxml_max_entrys || $this->sitemapFileSize+strlen($itemRecord) >= $this->sitemapxml_max_size) {
      $this->_SitemapCloseFile();

      $this->sitemapFileName = $this->_getNameFileXML($this->sitemapFile . substr('000' . $this->sitemapFileNameNumber, -3));
      if (!$this->_fileOpen($this->sitemapFileName)) return false;
      $this->_SitemapReSetFile();
      $this->sitemapFileBuffer .= $this->_SitemapXMLHeader();
    }
    $this->sitemapFileBuffer .= $itemRecord;
    $this->_fileWrite($this->sitemapFileBuffer);
    $this->sitemapFileSize += strlen($this->sitemapFileBuffer);
    $this->sitemapFileSizeTotal += strlen($this->sitemapFileBuffer);
    $this->sitemapFileItems++;
    $this->sitemapFileItemsTotal++;
    $this->sitemapFileBuffer = '';
    return true;
  }

  function SitemapClose() {
    global $db;
    $this->_SitemapCloseFile();
    if ($this->sitemapFileItemsTotal > 0) {
      $time_end = explode (' ', microtime());
      $time_end = $time_end[1]+$time_end[0];
      $total_time = $time_end-$this->statisticModuleTime;
      $total_queries = $db->count_queries - $this->statisticModuleQueries;
      $total_queries_time = $db->total_query_time - $this->statisticModuleQueriesTime;
      echo sprintf(TEXT_TOTAL_SITEMAP, ($this->sitemapFileNameNumber+1), $this->sitemapFileItemsTotal, $this->sitemapFileSizeTotal, $this->timefmt($total_time), $total_queries, $this->timefmt($total_queries_time)) . '<br />';
    }
    $this->_SitemapReSet();
  }

// generate sitemap index file
  function GenerateSitemapIndex() {
    if ($this->genxml) {
      echo '<h3>' . TEXT_HEAD_SITEMAP_INDEX . '</h3>';
      $this->SitemapOpen('index', 0, 'index');
      $content = $this->_SitemapXMLHeader();
      $records_count = 0;
      $pattern = '/^' . $this->sitemap . '.*(\.xml' . ($this->compress ? '|\.xml\.gz' : '') . ')$/';
      if ($za_dir = @dir(rtrim($this->savepath, '/'))) {
        clearstatcache();
        while ($filename = $za_dir->read()) {
          if (preg_match($pattern, $filename) > 0 && $filename != $this->sitemapindex && filesize($this->savepath . $filename) > 0) {
            echo TEXT_INCLUDE_FILE . $filename . ' (<a href="' . $this->base_url . basename($filename) . '" target="_blank">' . $this->base_url . basename($filename) . '</a>)' . '<br />';
            $content .= ' <sitemap>' . "\n";
            $content .= '  <loc>' . $this->base_url . basename($filename) . '</loc>' . "\n";
            $content .= '  <lastmod>' . $this->_LastModFormat(filemtime($this->savepath . $filename)) . '</lastmod>' . "\n";
            $content .= ' </sitemap>' . "\n";
            $records_count++;
          }
        }
      }
      $content .= '</sitemapindex>';
      $this->_SaveFileXML($content, 'index', $records_count);
    }

    if ($this->inline) {
      if ($this->submitFlag) {
        ob_end_clean();
        $this->_outputSitemapIndex();
      } else {
        ob_end_flush();
      }
    }

    if ($this->ping) {
      $this->_SitemapPing();
    }

    if ($this->inline) {
      die();
    }

  }

// retrieve full cPath from category ID
  function GetFullcPath($cID) {
    global $db;
    static $parent_cache = array();
    $cats = array();
    $cats[] = $cID;
    $parent = $db->Execute("SELECT parent_id, categories_id
                            FROM " . TABLE_CATEGORIES . "
                            WHERE categories_id=" . (int)$cID);
    while(!$parent->EOF && $parent->fields['parent_id'] != 0) {
      $parent_cache[(int)$parent->fields['categories_id']] = (int)$parent->fields['parent_id'];
      $cats[] = $parent->fields['parent_id'];
      if (isset($parent_cache[(int)$parent->fields['parent_id']])) {
        $parent->fields['parent_id'] = $parent_cache[(int)$parent->fields['parent_id']];
      } else {
        $parent = $db->Execute("SELECT parent_id, categories_id
                                FROM " . TABLE_CATEGORIES . "
                                WHERE categories_id=" . (int)$parent->fields['parent_id']);
      }
    }
    $cats = array_reverse($cats);
    $cPath = implode('_', $cats);
    return $cPath;
  }

  function setCheckURL($checkurl) {
    $this->checkurl = $checkurl;
  }

  function setStylesheet($stylesheet) {
    $this->stylesheet = $stylesheet;
  }

  function getLanguageParameter($language_id, $lang_parm='language') {
    $code = '';
    if (!isset($language_id) || $language_id == 0) {
      $language_id = $this->default_language_id;
    }
    if (isset($this->languagesCodes[$language_id])) {
      if (($this->languagesCodes[$language_id] != DEFAULT_LANGUAGE && $this->languagesCount > 1) || SITEMAPXML_USE_DEFAULT_LANGUAGE == 'true') {
        $code = '&' . $lang_parm . '=' . $this->languagesCodes[$language_id];
      }
    } else {
      $code = false;
    }
    return $code;
  }

  function getLanguageDirectory($language_id) {
    if (isset($this->languagesDirectory[$language_id])) {
      $directory = $this->languagesDirectory[$language_id];
    } else {
      $directory = false;
    }
    return $directory;
  }

  function getLanguagesIDs() {
    return $this->languagesIDs;
  }

  function getLanguagesCodes() {
    return $this->languagesCodes;
  }

/////////////////////////

  function _checkFTimeSitemap($filename, $last_date=0) {
// TODO: Multifiles
    if ($this->rebuild == true) return true;
    if ($last_date == 0) return true;
    clearstatcache();
    if ( SITEMAPXML_USE_EXISTING_FILES == 'true'
      && file_exists($this->savepath . $filename)
      && (filemtime($this->savepath . $filename) >= strtotime($last_date))
      && filesize($this->savepath . $filename) > 0) {
      echo '"' . $filename . '" ' . TEXT_FILE_NOT_CHANGED . '<br />';
      return false;
    }
    return true;
  }

  function _getNameFileXML($filename) {
    switch ($this->sitemapType) {
      case 'index':
        $filename = $this->sitemapindex;
        break;
      case 'video':
        $filename = $this->videomap . $filename . '.xml' . ($this->compress ? '.gz' : '');
        break;
      case 'sitemap':
      default:
        $filename = $this->sitemap . $filename . '.xml' . ($this->compress ? '.gz' : '');
        break;
    }
    return $filename;
  }

// save the sitemap data to file as either .xml or .xml.gz format
  function _SaveFileXML($data, $type, $records=0, $skipped=0) {
    $ret = true;
    $filename = $this->_getNameFileXML($type);
//    echo 'Output file: ' . $this->savepath . $filename . '<br />';
    if (substr($filename, -3) == '.gz') {
      if ($gz = gzopen($this->savepath . $filename,'wb9')) {
        gzwrite($gz, $data, strlen($data));
        gzclose($gz);
      } else {
        $ret = false;
      }
    } else {
      if ($fp = fopen($this->savepath . $filename, 'w+')) {
        fwrite($fp, $data, strlen($data));
        fclose($fp);
      } else {
        $ret = false;
      }
    }
    if (!$ret) {
      echo '<span style="font-weight: bold); color: red;"> ' . TEXT_FAILED_TO_OPEN . ' "' . $filename . '"!!!</span>' . '<br />';
      $this->submitFlag = false;
    } else {
      echo TEXT_URL_FILE . '<a href="' . $this->base_url . $filename . '" target="_blank">' . $this->base_url . $filename . '</a>' . '<br />';
      echo sprintf(TEXT_WRITTEN, $records, strlen($data), filesize($filename)) . '<br />';
    }
    return $ret;
  }

// format the LastMod field
  function _LastModFormat($date) {
    if (SITEMAPXML_LASTMOD_FORMAT == 'full') {
      return gmdate('Y-m-d\TH:i:s', $date) . $this->timezone;
    } else {
      return gmdate('Y-m-d', $date);
    }
  }

  function _SitemapXMLHeader() {
    $header = '';
    $header .= '<?xml version="1.0" encoding="UTF-8"?'.'>' . "\n";
    $header .= ($this->stylesheet != '' ? '<?xml-stylesheet type="text/xsl" href="' . $this->stylesheet . '"?'.'>' . "\n" : "");
    switch ($this->sitemapType) {
      case 'index':
        $header .= '<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . "\n";
        $header .= '        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd"' . "\n";
        $header .= '        xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        break;
      case 'video':
        $header .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        $header .= '        xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">' . "\n";
        break;
      case 'sitemap':
      default:
        $header .= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . "\n";
        $header .= '        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"' . "\n";
        $header .= '        xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        break;
    }
    $header .= '<!-- generator="Zen-Cart SitemapXML" ' . SITEMAPXML_VERSION . ' -->' . "\n";
    $header .= '<!-- ' . $this->sitemapFileName . ' created at ' . date('Y-m-d H:i:s') . ' -->' . "\n";
    return $header;
  }

  function _SitemapPing() {
    if ($this->submitFlag && SITEMAPXML_PING_URLS !== '') {
      echo '<h3>' . TEXT_HEAD_PING . '</h3>';
      $pingURLs = explode(";", SITEMAPXML_PING_URLS);
      foreach ($pingURLs as $pingURL) {
        $pingURLarray = explode("=>", $pingURL);
        if (!isset($pingURLarray[1])) $pingURLarray[1] = $pingURLarray[0];
        $pingURLarray[0] = trim($pingURLarray[0]);
        $pingURLarray[1] = trim($pingURLarray[1]);
        $pingFullURL = sprintf($pingURLarray[1], $this->submit_url);
        echo '<h4>' . TEXT_HEAD_PING . ' ' . $pingURLarray[0] . '</h4>';
        echo $pingFullURL . '<br />';
        echo '<div style="background-color: #FFFFCC); border: 1px solid #000000; padding: 5px">';
        if ($info = $this->_curlExecute($pingFullURL, 'page')) {
          echo $this->_clearHTML($info['html_page']);
        }
        echo '</div>';
      }
    }
  }

  function _clearHTML($html) {
    $html = str_replace("&nbsp;", " ", $html);
    $html = preg_replace("@\s\s+@", " ", $html);
    $html = preg_replace('@<head>(.*)</'.'head>@si', '', $html);
    $html = preg_replace('@<script(.*)</'.'script>@si', '', $html);
    $html = preg_replace('@<title>(.*)</'.'title>@si', '', $html);
  	$html = preg_replace('@(<br\s*[/]*>|<p.*>|</p>|</div>|</h\d+>)@si', "$1\n", $html);
    $html = preg_replace("@\n\s+@", "\n", $html);
    $html = strip_tags($html);
    $html = trim($html);
    $html = nl2br($html);
    return $html;
  }

  function _outputSitemapIndex() {
    header('Last-Modified: ' . gmdate('r') . ' GMT');
    header('Content-Type: text/xml; charset=UTF-8');
    header('Content-Length: ' . filesize($this->savepath . $this->sitemapindex));
//    header('Content-disposition: inline; filename=' . $this->sitemapindex);
    echo file_get_contents($this->savepath . $this->sitemapindex);
  }

  function _curlExecute($url, $read='page') {
    if (!function_exists('curl_init')) {
      echo TEXT_ERROR_CURL_NOT_FOUND . '<br />';
      return false;
    }
    if (!$ch = curl_init()) {
      echo TEXT_ERROR_CURL_INIT . '<br />';
      return false;
    }
    $url = str_replace('&amp;', '&', $url);
    curl_setopt($ch, CURLOPT_URL, $url);
    if ($read == 'page') {
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_NOBODY, 0);
      @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    } else {
      curl_setopt($ch, CURLOPT_HEADER, 1);
      curl_setopt($ch, CURLOPT_NOBODY, 1);
      @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);

    if (CURL_PROXY_REQUIRED == 'True') {
      $proxy_tunnel_flag = (defined('CURL_PROXY_TUNNEL_FLAG') && strtoupper(CURL_PROXY_TUNNEL_FLAG) == 'FALSE') ? false : true;
      curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
      curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
      curl_setopt($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);
    }

    if (!$result = curl_exec($ch)) {
      echo sprintf(TEXT_ERROR_CURL_EXEC, curl_error($ch), $url) . '<br />';
      return false;
    } else {
      $info = curl_getinfo($ch);
      curl_close($ch);
      if (empty($info['http_code'])) {
        echo sprintf(TEXT_ERROR_CURL_NO_HTTPCODE, $url) . '<br />';
//        return false;
      } elseif ($info['http_code'] != 200) {
//        $http_codes = @parse_ini_file('includes/http_responce_code.ini');
//        echo "cURL Error: Error http_code '<b>" . $info['http_code'] . " " . $http_codes[$info['http_code']] . "</b>' reading '" . $url . "'. " . '<br />';
        echo sprintf(TEXT_ERROR_CURL_ERR_HTTPCODE, $info['http_code'], $url) . '<br />';
//        return false;
      }
      if ($read == 'page') {
        if ($info['size_download'] == 0) {
          echo sprintf(TEXT_ERROR_CURL_0_DOWNLOAD, $url) . '<br />';
//          return false;
        }
        if (isset($info['download_content_length']) && $info['download_content_length'] > 0 && $info['download_content_length'] != $info['size_download']) {
          echo sprintf(TEXT_ERROR_CURL_ERR_DOWNLOAD, $url, $info['size_download'], $info['download_content_length']) . '<br />';
//          return false;
        }
        $info['html_page'] = $result;
      }
    }
    return $info;
  }

///////////////////////
  function _SitemapReSet() {
    $this->_SitemapReSetFile();
    $this->sitemapFileItemsTotal = 0;
    $this->sitemapFileSizeTotal = 0;
    $this->sitemapFileNameNumber = 0;
    $this->sitemapFileItemsMax = 0;
    $this->duplicatedLinks = array();
    return true;
  }

  function _SitemapReSetFile() {
//    $this->sitemapFile = null;
//    $this->sitemapType = null;
//    $this->sitemapFileName = null;
    $this->sitemapFileBuffer = '';
    $this->sitemapFileItems = 0;
    $this->sitemapFileSize = 0;
    $this->sitemapFileNameNumber++;
    return true;
  }

  function _SitemapCloseFile() {
    if (!$this->_fileIsOpen()) return;
    if ($this->sitemapFileItems > 0) {
      $this->sitemapFileBuffer .= $this->sitemapFileFooter;
      $this->sitemapFileSizeTotal += strlen($this->sitemapFileBuffer);
      $this->_fileWrite($this->sitemapFileBuffer);
    }
    $this->_fileClose();
    echo sprintf(TEXT_FILE_SITEMAP_INFO, $this->base_url . $this->sitemapFileName, $this->base_url . $this->sitemapFileName, $this->sitemapFileItems, $this->sitemapFileSize, filesize($this->sitemapFileName)) . '<br />';
  }

  function statisticModuleReset() {
    global $db;
    $this->statisticModuleTime = explode (' ', microtime());
    $this->statisticModuleTime = $this->statisticModuleTime[1]+$this->statisticModuleTime[0];
    $this->statisticModuleQueries = $db->count_queries;
    $this->statisticModuleQueriesTime = $db->total_query_time;
  }

///////////////////////
  function _fileOpen($filename) {
    $this->fn = $filename;
    $this->fb = '';
    if (substr($this->fn, -3) == '.gz') {
      $this->fp = gzopen($this->savepath . $filename,'wb9');
    } else {
      $this->fp = fopen($this->savepath . $filename, 'w+');
    }
    if (!$this->fp) {
      echo '<span style="font-weight: bold); color: red;"> ' . TEXT_FAILED_TO_OPEN . ' "' . $filename . '"!!!</span>' . '<br />';
      $this->submitFlag = false;
    }
    return $this->fp;
  }

  function _fileIsOpen() {
    if (is_null($this->fp)) return false;
    return true;
  }

  function _fileWrite($data='') {
    $ret = true;
    if (strlen($this->fb) > $this->fb_maxsize || ($data == '' && strlen($this->fb) > 0)) {
      if (substr($this->fn, -3) == '.gz') {
        $ret = gzwrite($this->fp, $this->fb, strlen($this->fb));
      } else {
        $ret = fwrite($this->fp, $this->fb, strlen($this->fb));
      }
      $this->fb = '';
    }
    $this->fb .= $data;
    return $ret;
  }

  function _fileClose() {
    if (!$this->fp) return;
    if (strlen($this->fb) > 0) {
      $this->_fileWrite();
    }
    if (substr($this->fn, -3) == '.gz') {
      gzclose($this->fp);
    } else {
      fclose($this->fp);
    }
    $this->fp = null;
  }

  function timefmt($s) {
    $m = floor($s/60);
    $s = $s - $m*60;
    return $m . ":" . number_format($s, 4);
  }

  function _clear_url($str) {
    $url_parts = parse_url($str);
    $out = '';
    if (isset($url_parts["scheme"])) $out .= $url_parts["scheme"] . '://';
    if (isset($url_parts["host"])) $out .= $url_parts["host"];
    if (isset($url_parts["port"])) $out .= ':' . $url_parts["port"];
    if (isset($url_parts["path"])) {
      $pathinfo = pathinfo($url_parts["path"]);
      if (!isset($pathinfo["dirname"]) || $pathinfo["dirname"] == '\\' || $pathinfo["dirname"] == '.') $pathinfo["dirname"] = '';
      $out .= rtrim($pathinfo["dirname"], '/') . '/';
      if ($pathinfo["basename"] != '') {
        $out .= str_replace('&', '%26', rawurlencode($pathinfo["basename"]));
      }
    }
    if (isset($url_parts["query"])) {
      $url_parts["query"] = str_replace('&amp;', '&', $url_parts["query"]);
      $url_parts["query"] = str_replace('&&', '&', $url_parts["query"]);
      $url_parts["query"] = str_replace('&', '&amp;', $url_parts["query"]);
      $out .= '?' . $url_parts["query"];
    }
    if (isset($url_parts["fragment"])) $out .= '#' . $url_parts["fragment"];
    $out = $this->_utf8_encode($out);
    return $out;
  }

  function _utf8_encode($str) {
    if (is_null($this->convert_to_utf8)) {
      $this->convert_to_utf8 = (strtolower(CHARSET) != 'utf-8');
    }
    if ($this->convert_to_utf8 === true) {
      if (preg_match('@[\x7f-\xff]@', $str)) {
        $str = iconv(CHARSET, 'utf-8', $str);
      }
    }
    return $str;
  }

  function _clear_string($str) {
    $str = $this->_clear_problem_characters($str);
    $str = html_entity_decode($str, ENT_QUOTES);
    $str = $this->_utf8_encode($str);
    $str = htmlspecialchars($str);
    $str = strip_tags($str);
    return $str;
  }

  function _clear_problem_characters($str) {
    $formattags = array("&");
    $replacevals = array("&#38;");
//    $str = str_replace($formattags, $replacevals, $str);
    $in = $out = array();
    $in[] = '@&(amp|#038);@i'; $out[] = '&';
    $in[] = '@&(#036);@i'; $out[] = '$';
    $in[] = '@&(quot);@i'; $out[] = '"';
    $in[] = '@&(#039);@i'; $out[] = '\'';
    $in[] = '@&(nbsp|#160);@i'; $out[] = ' ';
    $in[] = '@&(hellip|#8230);@i'; $out[] = '...';
    $in[] = '@&(copy|#169);@i'; $out[] = '(c)';
    $in[] = '@&(trade|#129);@i'; $out[] = '(tm)';
    $in[] = '@&(lt|#60);@i'; $out[] = '<';
    $in[] = '@&(gt|#62);@i'; $out[] = '>';
    $in[] = '@&(laquo);@i'; $out[] = '«';
    $in[] = '@&(raquo);@i'; $out[] = '»';
    $in[] = '@&(deg);@i'; $out[] = '°';
    $in[] = '@&(mdash);@i'; $out[] = '—';
    $in[] = '@&(reg);@i'; $out[] = '®';
    $in[] = '@&(–);@i'; $out[] = '-';
    $str = preg_replace($in, $out, $str);
    return $str;
  }

}