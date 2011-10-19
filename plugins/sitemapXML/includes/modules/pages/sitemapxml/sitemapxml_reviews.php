<?php
/**
 * Sitemap XML
 *
 * @package Sitemap XML
 * @copyright Copyright 2005-2010, Andrew Berezin eCommerce-Service.com
 * @copyright Portions Copyright 2003-2008 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: sitemapxml_reviews.php, v 2.3.1 21.07.2010 9:18:18 AndrewBerezin $
 */

echo '<h3>' . TEXT_HEAD_REVIEWS . '</h3>';
$last_date = $db->Execute("SELECT MAX(GREATEST(r.date_added, IFNULL(r.last_modified, 0))) AS last_date
                           FROM " . TABLE_REVIEWS . " r
                           WHERE r.status = '1'");
$table_status = $db->Execute("SHOW TABLE STATUS LIKE '" . TABLE_REVIEWS . "'");
$last_date = max($table_status->fields['Update_time'], $last_date->fields['last_date']);
if ($zen_SiteMapXML->SitemapOpen('reviews', $last_date)) {
  $reviews = $db->Execute("SELECT r.reviews_id, GREATEST(r.date_added, IFNULL(r.last_modified, '0001-01-01 00:00:00')) AS last_date, r.products_id, r.reviews_rating AS priority, rd.languages_id AS language_id
                         FROM " . TABLE_REVIEWS . " r
                           LEFT JOIN " . TABLE_REVIEWS_DESCRIPTION . " rd ON (r.reviews_id = rd.reviews_id)
                         WHERE r.status = '1'
                           AND rd.languages_id IN (" . $zen_SiteMapXML->getLanguagesIDs() . ") " .
                         (SITEMAPXML_REVIEWS_ORDERBY != '' ? "ORDER BY " . SITEMAPXML_REVIEWS_ORDERBY : ''));
  $zen_SiteMapXML->SitemapSetMaxItems($reviews->RecordCount());
  while(!$reviews->EOF) {
    $langParm = $zen_SiteMapXML->getLanguageParameter($reviews->fields['languages_id']);
    if ($langParm !== false) {
      $link = zen_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $reviews->fields['products_id'] . '&reviews_id=' . $reviews->fields['reviews_id'] . $langParm, 'NONSSL', false);
      $zen_SiteMapXML->SitemapWriteItem($link, strtotime($reviews->fields['last_date']), SITEMAPXML_REVIEWS_CHANGEFREQ);
    }
    $reviews->MoveNext();
  }
  $zen_SiteMapXML->SitemapClose();
}