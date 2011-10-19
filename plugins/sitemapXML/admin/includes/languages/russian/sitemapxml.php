<?php
/**
 * Sitemap XML Feed
 *
 * @package Sitemap XML Feed
 * @copyright Copyright 2005-2011, Andrew Berezin eCommerce-Service.com
 * @copyright Portions Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @link http://www.sitemaps.org/
 * @version $Id: sitemapxml.php, v 2.3.3 07.08.2011 18:20:49 AndrewBerezin $
 */

define('SITEMAPXML_SITEMAPINDEX_HTTP_LINK', HTTP_CATALOG_SERVER . DIR_WS_CATALOG . SITEMAPXML_SITEMAPINDEX . '.xml');
define('HEADING_TITLE', 'Sitemap XML');
define('TEXT_SITEMAPXML_OVERVIEW_HEAD', 'Обзор:');
define('TEXT_SITEMAPXML_OVERVIEW_TEXT', 'Данный модуль автоматически создаёт несколько sitemapsXML для Вашего магазина: основной файл sitemapsXML, sitemapsXML для главной страницы, для категорий, товаров, отзывов на товары, производители, EZ-страницы, отзывы на магазин. <br />
<p>Подробно о Sitemaps xml Вы можете прочитать на <strong><a href="http://sitemaps.org/" target="_blank" class="splitPageLink">[Sitemaps.org]</a></strong>.</p>
<ol>
<li>Зарегистрируйтесь: <strong><a href="https://www.google.com/webmasters/sitemaps/login" target="_blank" class="splitPageLink">[Google]</a></strong>, <strong><a href="http://webmaster.yandex.ru/" target="_blank" class="splitPageLink">[Yandex]</a></strong>, <strong><a href="http://siteexplorer.search.yahoo.com/" target="_blank" class="splitPageLink">[Yahoo!]</a></strong>, <strong><a href="http://ask.com" target="_blank" class="splitPageLink">[Ask.com]</a></strong>, <strong><a href="http://login.live.com/login.srf?wa=wsignin1.0&rpsnv=10&ct=1244808469&rver=5.5.4177.0&wp=MBI&wreply=http:%2F%2Fwww.bing.com%2FPassport.aspx%3Frequrl%3Dhttp%253a%252f%252fwww.bing.com%253a80%252f&lc=1049&id=264960" target="_blank" class="splitPageLink">[bing]</a></strong>.</li>
<li>Укажите Ваш Sitemap <input type="text" readonly="readonly" value="' . SITEMAPXML_SITEMAPINDEX_HTTP_LINK . '" size="' . strlen(SITEMAPXML_SITEMAPINDEX_HTTP_LINK) . '" style="border: solid 1px; padding: 0 4px 0 4px;"/> в <strong><a href="https://www.google.com/webmasters/tools/home" target="_blank" class="splitPageLink">[Google]</a></strong>, <strong><a href="http://webmaster.yandex.ru/" target="_blank" class="splitPageLink">[Yandex]</a></strong>, <strong><a href="http://siteexplorer.search.yahoo.com/" target="_blank" class="splitPageLink">[Yahoo!]</a></strong>, <strong><a href="http://ask.com" target="_blank" class="splitPageLink">[Ask.com]</a></strong>, <strong><a href="http://www.bing.com/webmaster/WebmasterAddSitesPage.aspx" target="_blank" class="splitPageLink">[bing]</a></strong>.</li>
<li>Укажите адрес Sitemap в Вашем файле <a href="' . HTTP_CATALOG_SERVER . DIR_WS_CATALOG . 'robots.txt' . '" target="_blank" class="splitPageLink">robots.txt</a> (<a href="http://sitemaps.org/protocol.php#submit_robots" target="_blank" class="splitPageLink">подробнее...</a>):<br /><input type="text" readonly="readonly" value="Sitemap: ' . SITEMAPXML_SITEMAPINDEX_HTTP_LINK . '" size="' . strlen('Sitemap: ' . SITEMAPXML_SITEMAPINDEX_HTTP_LINK) . '" style="border: solid 1px; padding: 0 4px 0 4px;"/></li>
<li>Оповестите поисковые системы об изменениях Ваших Sitemap XML.</li>
</ol>');
define('TEXT_SITEMAPXML_TIPS_HEAD', 'Советы:');
define('TEXT_SITEMAPXML_TIPS_TEXT', 'Чтобы автоматически обновлять sitemaps и автоматически оповещать (пинговать) поисковые системы, необходимо создать cron-задания в Вашей управляющей панели Вашего хостинга.<br />
Например, для запуска задания ежедневно в 5:0 утра, задайте следующие параметры задания cron (конкретные команды могут отличаться в зависимости от хостинга):
<div>
0 5 * * * GET \'http://your_domain/index.php?main_page=sitemapxml\&amp;rebuild=yes\&amp;ping=yes\'<br />
or<br />
0 5 * * * wget -q \'http://your_domain/index.php?main_page=sitemapxml\&amp;rebuild=yes\&amp;ping=yes\' -O /dev/null<br />
or<br />
0 5 * * * curl -s \'http://your_domain/index.php?main_page=sitemapxml\&amp;rebuild=yes\&amp;ping=yes\'<br />
or<br />
0 5 * * * php -f &lt;path to shop&gt;/cgi-bin/sitemapxml.php rebuild=yes ping=yes<br />
</div>');

//zen_catalog_href_link(SITEMAPXML_SITEMAPINDEX . '.xml')
define('TEXT_SITEMAPXML_INSTRUCTIONS_HEAD', 'Создать / обновить Ваши Sitemap:');
define('TEXT_SITEMAPXML_CHOOSE_PARAMETERS', 'Выберите параметры:');
define('TEXT_SITEMAPXML_CHOOSE_PARAMETERS_PING', 'Пинговать поисковые системы ');
define('TEXT_SITEMAPXML_CHOOSE_PARAMETERS_REBUILD', 'Перезаписать все существующие файлы sitemap*.xml!');
define('TEXT_SITEMAPXML_CHOOSE_PARAMETERS_INLINE', 'Показать файл ' . SITEMAPXML_SITEMAPINDEX . '.xml');

define('TEXT_SITEMAPXML_INSTALL_HEAD', 'Замечания по установке:');

define('TEXT_SITEMAPXML_INSTALL_DELETE_FILE', 'Удалите этот файл');

///////////
define('TEXT_INSTALL', 'Установить SitemapXML SQL');
define('TEXT_UPGRADE', 'Обновить SitemapXML SQL');
define('TEXT_UNINSTALL', 'Удалить SitemapXML SQL');
define('TEXT_UPGRADE_CONFIG_ADD', '');
define('TEXT_UPGRADE_CONFIG_UPD', '');
define('TEXT_UPGRADE_CONFIG_DEL', '');

///////////
define('TEXT_CONFIGURATION_TITLE_SITEMAPXML_VERSION', 'Версия скрипта');
define('TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_VERSION', '<img src="images/icon_popup.gif" border="0">&nbsp;<a href="http://ecommerce-service.com/" target="_blank" style="text-decoration: underline; font-weight: bold;">eCommerce Service</a>');
define('TEXT_CONFIGURATION_TITLE_SITEMAPXML_SITEMAPINDEX', 'Имя индексного файла SitemapXML');
define('TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_SITEMAPINDEX', 'Имя индексного файла SitemapXML - этот файл должен передаваться поисковым системам');
define('TEXT_CONFIGURATION_TITLE_SITEMAPXML_COMPRESS', 'Упаковывать файлы SitemapXML');
define('TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_COMPRESS', 'Упаковывать файлы SitemapXML');
define('TEXT_CONFIGURATION_TITLE_SITEMAPXML_LASTMOD_FORMAT', 'Формат тега Lastmod');
define('TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_LASTMOD_FORMAT', 'Формат тега Lastmod:<br />date - Полная дата: YYYY-MM-DD (например 1997-07-16)<br />full -    Полная дата плюс часы, минуты и секунды: YYYY-MM-DDThh:mm:ssTZD (например 1997-07-16T19:20:30+01:00)');
define('TEXT_CONFIGURATION_TITLE_SITEMAPXML_USE_EXISTING_FILES', 'Использовать существующие файлы XML');
define('TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_USE_EXISTING_FILES', 'Использовать существующие файлы XML или перезаписывать их');
define('TEXT_CONFIGURATION_TITLE_SITEMAPXML_USE_DEFAULT_LANGUAGE', 'Генерировать параметр language для языка по умолчанию');
define('TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_USE_DEFAULT_LANGUAGE', 'Генерировать в ссылках параметр language для языка по умолчанию');
define('TEXT_CONFIGURATION_TITLE_SITEMAPXML_PING_URLS', 'Адреса пингуемых сервисов');
define('TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_PING_URLS', 'Адреса пингуемых сервисов перечисленные через ;');
define('TEXT_CONFIGURATION_TITLE_SITEMAPXML_HOMEPAGE_ORDERBY', 'Домашняя страница - order by');
define('TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_HOMEPAGE_ORDERBY', '');
define('TEXT_CONFIGURATION_TITLE_SITEMAPXML_HOMEPAGE_CHANGEFREQ', 'Домашняя страница - changefreq');
define('TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_HOMEPAGE_CHANGEFREQ', 'Вероятная частота изменения Домашней страницы');
define('TEXT_CONFIGURATION_TITLE_SITEMAPXML_PRODUCTS_ORDERBY', 'Товары - order by');
define('TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_PRODUCTS_ORDERBY', '');
define('TEXT_CONFIGURATION_TITLE_SITEMAPXML_PRODUCTS_CHANGEFREQ', 'Товары - changefreq');
define('TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_PRODUCTS_CHANGEFREQ', 'Вероятная частота изменения страницы Товаров');
define('TEXT_CONFIGURATION_TITLE_SITEMAPXML_CATEGORIES_ORDERBY', 'Категории - order by');
define('TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_CATEGORIES_ORDERBY', '');
define('TEXT_CONFIGURATION_TITLE_SITEMAPXML_CATEGORIES_CHANGEFREQ', 'Категории - changefreq');
define('TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_CATEGORIES_CHANGEFREQ', 'Вероятная частота изменения страницы Категорий');
define('TEXT_CONFIGURATION_TITLE_SITEMAPXML_REVIEWS_ORDERBY', 'Отзывы на товары - order by');
define('TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_REVIEWS_ORDERBY', '');
define('TEXT_CONFIGURATION_TITLE_SITEMAPXML_REVIEWS_CHANGEFREQ', 'Отзывы на товары - changefreq');
define('TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_REVIEWS_CHANGEFREQ', 'Вероятная частота изменения страницы Отзывов');
define('TEXT_CONFIGURATION_TITLE_SITEMAPXML_EZPAGES_ORDERBY', 'EZ-Страницы - order by');
define('TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_EZPAGES_ORDERBY', '');
define('TEXT_CONFIGURATION_TITLE_SITEMAPXML_EZPAGES_CHANGEFREQ', 'EZ-Страницы - changefreq');
define('TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_EZPAGES_CHANGEFREQ', 'Вероятная частота изменения EZ-Страницы');
define('TEXT_CONFIGURATION_TITLE_SITEMAPXML_TESTIMONIALS_ORDERBY', 'Отзывы на магазин - order by');
define('TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_TESTIMONIALS_ORDERBY', '');
define('TEXT_CONFIGURATION_TITLE_SITEMAPXML_TESTIMONIALS_CHANGEFREQ', 'Отзывы на магазин - changefreq');
define('TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_TESTIMONIALS_CHANGEFREQ', 'Вероятная частота изменения страницы Отзывов на магазин');
define('TEXT_CONFIGURATION_TITLE_SITEMAPXML_NEWS_ORDERBY', 'News Articles - order by');
define('TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_NEWS_ORDERBY', '');
define('TEXT_CONFIGURATION_TITLE_SITEMAPXML_NEWS_CHANGEFREQ', 'News Articles - changefreq');
define('TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_NEWS_CHANGEFREQ', 'Вероятная частота изменения страницы News Articles');
define('TEXT_CONFIGURATION_TITLE_SITEMAPXML_MANUFACTURERS_ORDERBY', 'Бренды - order by');
define('TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_MANUFACTURERS_ORDERBY', '');
define('TEXT_CONFIGURATION_TITLE_SITEMAPXML_MANUFACTURERS_CHANGEFREQ', 'Бренды - changefreq');
define('TEXT_CONFIGURATION_DESCRIPTION_SITEMAPXML_MANUFACTURERS_CHANGEFREQ', 'Вероятная частота изменения страницы Брендов');
//define('TEXT_CONFIGURATION_TITLE_', '');
//define('TEXT_CONFIGURATION_DESCRIPTION_', '');

// EOF