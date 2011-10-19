<?php
/**
 * Sitemap XML
 *
 * @package Sitemap XML
 * @copyright Copyright 2005-2011, Andrew Berezin eCommerce-Service.com
 * @copyright Portions Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: sitemapxml_manufacturers.php, v 1.0.1 21.07.2010 9:18:18 AndrewBerezin $
 */

echo '<h3>' . TEXT_HEAD_MANUFACTURERS . '</h3>';
$last_date = $db->Execute("SELECT MAX(GREATEST(IFNULL(m.date_added, 0), IFNULL(m.last_modified, 0))) AS last_date
                           FROM " . TABLE_MANUFACTURERS . " m");
$table_status = $db->Execute("SHOW TABLE STATUS LIKE '" . TABLE_MANUFACTURERS . "'");
$last_date = max($table_status->fields['Update_time'], $last_date->fields['last_date']);
if ($zen_SiteMapXML->SitemapOpen('manufacturers', $last_date)) {
    $manufacturers = $db->Execute("SELECT m.manufacturers_id, GREATEST(m.date_added, IFNULL(m.last_modified, '0001-01-01 00:00:00')) AS last_date, mi.languages_id
                              FROM " . TABLE_MANUFACTURERS . " m
                                LEFT JOIN " . TABLE_MANUFACTURERS_INFO . " mi ON (mi.manufacturers_id = m.manufacturers_id)
                              WHERE mi.languages_id IN (" . $zen_SiteMapXML->getLanguagesIDs() . ") " .
                              (SITEMAPXML_MANUFACTURERS_ORDERBY != '' ? "ORDER BY " . SITEMAPXML_MANUFACTURERS_ORDERBY : ''));
  $zen_SiteMapXML->SitemapSetMaxItems($manufacturers->RecordCount());
  while(!$manufacturers->EOF) {
    $langParm = $zen_SiteMapXML->getLanguageParameter($manufacturers->fields['languages_id']);
    if ($langParm !== false) {
      $link = zen_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturers->fields['manufacturers_id'] . $langParm, 'NONSSL', false);
      $zen_SiteMapXML->SitemapWriteItem($link, strtotime($manufacturers->fields['last_date']), SITEMAPXML_MANUFACTURERS_CHANGEFREQ);
    }
    $manufacturers->MoveNext();
  }
  $zen_SiteMapXML->SitemapClose();
}