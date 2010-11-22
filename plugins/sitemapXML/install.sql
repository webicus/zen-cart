SET @configuration_group_id=0;
SELECT (@configuration_group_id:=configuration_group_id) FROM configuration_group WHERE configuration_group_title= 'Google XML Sitemap' LIMIT 1;
DELETE FROM configuration WHERE configuration_group_id = @configuration_group_id;
DELETE FROM configuration_group WHERE configuration_group_id = @configuration_group_id;

INSERT INTO configuration_group (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES (NULL, 'Google XML Sitemap', 'Google XML Sitemap Configuration', '1', '1');
SET @configuration_group_id=last_insert_id();
UPDATE configuration_group SET sort_order = @configuration_group_id WHERE configuration_group_id = @configuration_group_id;

INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES 
(NULL, 'Compress XML File', 'GOOGLE_SITEMAP_COMPRESS', 'false', 'Compress Google XML Sitemap file', @configuration_group_id, 1, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Products changefreq', 'GOOGLE_SITEMAP_PROD_CHANGE_FREQ', 'weekly', 'How frequently the Product pages page is likely to change.', @configuration_group_id, 2, NOW(), NULL, 'zen_cfg_select_option(array(\'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\', \'yearly\', \'never\'),'),
(NULL, 'Category changefreq', 'GOOGLE_SITEMAP_CAT_CHANGE_FREQ', 'weekly', 'How frequently the Category pages page is likely to change.', @configuration_group_id, 3, NOW(), NULL, 'zen_cfg_select_option(array(\'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\', \'yearly\', \'never\'),'),
(NULL, 'Lastmod tag format', 'GOOGLE_SITEMAP_LASTMOD_FORMAT', 'date', 'Lastmod tag format:<br />date - Complete date: YYYY-MM-DD (eg 1997-07-16)<br />full -    Complete date plus hours, minutes and seconds: YYYY-MM-DDThh:mm:ssTZD (eg 1997-07-16T19:20:30+01:00)', @configuration_group_id, 4, NOW(), NULL, 'zen_cfg_select_option(array(\'date\', \'full\'),'),
(NULL, 'Category priority', 'GOOGLE_SITEMAP_CAT_CHANGE_PRIOR', '0.5', 'The default priority of the products URL. Valid values range from 0.0 to 1.0.', @configuration_group_id, 3, NOW(), NULL, NULL),
(NULL, 'Products priority', 'GOOGLE_SITEMAP_PROD_CHANGE_PRIOR', '0', 'The default priority of the products URL. Valid values range from 0.0 to 1.0.', @configuration_group_id, 5, NOW(), NULL, NULL),
(NULL, 'Use Google Sitemaps Stylesheet', 'GOOGLE_SITEMAP_USE_XSL', 'true', 'Google Sitemaps Stylesheet gss.xsl', @configuration_group_id, 6, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'XML directory', 'GOOGLE_SITEMAP_XML_FS_DIRECTORY', '', 'Directory using for saving XML files. Setting it to your root directory. If empty, Google Sitemap use DIR_FS_CATALOG directory.', @configuration_group_id, 7, NOW(), NULL, NULL),
(NULL, 'EZPages Header', 'GOOGLE_SITEMAP_EZPAGES_HEADER', 'true', 'Use EZPages Header links to feed sitemapezpages.xml?', @configuration_group_id, 10, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'EZPages Sidebox', 'GOOGLE_SITEMAP_EZPAGES_SIDEBOX', 'true', 'Use EZPages Sidebox links to feed sitemapezpages.xml?', @configuration_group_id, 10, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'EZPages Footer', 'GOOGLE_SITEMAP_EZPAGES_FOOTER', 'true', 'Use EZPages Footer links to feed sitemapezpages.xml?', @configuration_group_id, 10, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'EZPages changefreq', 'GOOGLE_SITEMAP_EZPAGES_CHANGE_FREQ', 'weekly', 'How frequently the EZPages pages page is likely to change.', @configuration_group_id, 3, NOW(), NULL, 'zen_cfg_select_option(array(\'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\', \'yearly\', \'never\'),'),
(NULL, 'EZPages priority', 'GOOGLE_SITEMAP_EZPAGES_CHANGE_PRIOR', '0.5', 'The default priority of the EZPages URL. Valid values range from 0.0 to 1.0.', @configuration_group_id, 3, NOW(), NULL, NULL);
