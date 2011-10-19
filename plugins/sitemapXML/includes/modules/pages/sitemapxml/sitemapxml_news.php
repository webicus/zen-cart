<?php
/**
 * Sitemap XML
 *
 * @package Sitemap XML
 * @copyright Copyright 2005-2010, Andrew Berezin eCommerce-Service.com
 * @copyright Portions Copyright 2003-2008 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: sitemapxml_news.php, v 2.3.1 21.07.2010 9:18:18 AndrewBerezin $
 */

/*
1. sitemap хмл для news:
страницы новости
страницы отзывов
страницы архивов
*/

if (defined('TABLE_NEWS_ARTICLES')) {

  echo '<h3>' . TEXT_HEAD_NEWS_ARTICLES . '</h3>';
  $last_date = $db->Execute("SELECT MAX(GREATEST(n.news_date_added, IFNULL(n.news_last_modified, 0), n.news_date_published)) AS last_date
                             FROM " . TABLE_NEWS_ARTICLES . " n
                             WHERE n.news_status = '1'
                               AND n.news_date_published <= NOW()");
  $table_status = $db->Execute("SHOW TABLE STATUS LIKE '" . TABLE_NEWS_ARTICLES . "'");
  $last_date = max($table_status->fields['Update_time'], $last_date->fields['last_date']);
  if ($zen_SiteMapXML->SitemapOpen('newsarticles', $last_date)) {
    $news = $db->Execute("SELECT n.article_id, GREATEST(n.news_date_added, IFNULL(n.news_last_modified, '0001-01-01 00:00:00'), n.news_date_published) AS last_date, nt.language_id AS language_id
                          FROM " . TABLE_NEWS_ARTICLES . " n
                            LEFT JOIN " . TABLE_NEWS_ARTICLES_TEXT . " nt ON (n.article_id = nt.article_id)
                          WHERE n.news_status = '1'
                            AND n.news_date_published <= NOW()
                            AND nt.news_article_text != ''" .
                          (SITEMAPXML_NEWS_ORDERBY != '' ? "ORDER BY " . SITEMAPXML_NEWS_ORDERBY : ''));
    $zen_SiteMapXML->SitemapSetMaxItems($news->RecordCount());
    while(!$news->EOF) {
      $langParm = $zen_SiteMapXML->getLanguageParameter($news->fields['language_id']);
      $link = zen_href_link(FILENAME_NEWS_ARTICLE, 'article_id=' . $news->fields['article_id'] . $langParm, 'NONSSL', false);
      $zen_SiteMapXML->SitemapWriteItem($link, strtotime($news->fields['last_date']), SITEMAPXML_NEWS_CHANGEFREQ);
      $news->MoveNext();
    }
    $zen_SiteMapXML->SitemapClose();
  }

  if (false) {
    echo '<h3>' . TEXT_HEAD_NEWS . '</h3>';
    if ($zen_SiteMapXML->SitemapOpen('news', $last_date)) {
      $news = $db->Execute("SELECT news_date_published
                            FROM " . TABLE_NEWS_ARTICLES . "
                            WHERE news_status = '1'
                              AND news_date_published <= NOW()
                            GROUP BY news_date_published DESC");
      $zen_SiteMapXML->SitemapSetMaxItems($news->RecordCount());
      $link_ym_array = array();
      while(!$news->EOF) {
        $date_ymd = substr($news->fields['news_date_published'], 0, 10);
        $date_ym  = substr($news->fields['news_date_published'], 0, 7);
        if (!isset($link_ym_array[$date_ym])) {
          $link = zen_href_link(FILENAME_NEWS_INDEX, 'date=' . $date_ym, 'NONSSL', false);
          $zen_SiteMapXML->SitemapWriteItem($link, strtotime($date_ym), SITEMAPXML_NEWS_CHANGEFREQ);
          $link_ym_array[$date_ym] = true;
        }
        $link = zen_href_link(FILENAME_NEWS_INDEX, 'date=' . $date_ymd, 'NONSSL', false);
        $zen_SiteMapXML->SitemapWriteItem($link, strtotime($date_ymd), SITEMAPXML_NEWS_CHANGEFREQ);
        $news->MoveNext();
      }
      $zen_SiteMapXML->SitemapClose();
    }
  }

}