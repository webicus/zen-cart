<?php
/**
 * Sitemap XML
 *
 * @package Sitemap XML
 * @copyright Copyright 2005-2011, Andrew Berezin eCommerce-Service.com
 * @copyright Portions Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: sitemapxml_homepage.php, v 1.0.1 05.08.2011 16:59:40 AndrewBerezin $
 */

echo '<h3>' . TEXT_HEAD_MAINPAGE . '</h3>';
$table_status = $db->Execute("SHOW TABLE STATUS LIKE '" . TABLE_LANGUAGES . "'");
$last_date = $table_status->fields['Update_time'];
if ($zen_SiteMapXML->SitemapOpen('mainpage', $last_date->fields['last_date'])) {
  $languages = $db->Execute("SELECT *
                             FROM " . TABLE_LANGUAGES . " l
                             WHERE l.languages_id IN (" . $zen_SiteMapXML->getLanguagesIDs() . ") " .
                             (SITEMAPXML_HOMEPAGE_ORDERBY != '' ? "ORDER BY " . SITEMAPXML_HOMEPAGE_ORDERBY : ''));
  $zen_SiteMapXML->SitemapSetMaxItems($languages->RecordCount());
  while(!$languages->EOF) {
    $langParm = $zen_SiteMapXML->getLanguageParameter($languages->fields['languages_id']);
    if ($langParm !== false) {
      $link = zen_href_link(FILENAME_DEFAULT, $langParm, 'NONSSL', false);
      $zen_SiteMapXML->SitemapWriteItem($link, strtotime($last_date->fields['last_date']), SITEMAPXML_HOMEPAGE_CHANGEFREQ);
    }
    $languages->MoveNext();
  }
  $zen_SiteMapXML->SitemapClose();
}
