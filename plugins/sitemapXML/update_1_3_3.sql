SET @configuration_group_id=0;
SELECT (@configuration_group_id:=configuration_group_id) FROM configuration_group WHERE configuration_group_title= 'Google XML Sitemap' LIMIT 1;

INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES 
(NULL, 'EZPages Header', 'GOOGLE_SITEMAP_EZPAGES_HEADER', 'true', 'Use EZPages Header links to feed sitemapezpages.xml?', @configuration_group_id, 10, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'EZPages Sidebox', 'GOOGLE_SITEMAP_EZPAGES_SIDEBOX', 'true', 'Use EZPages Sidebox links to feed sitemapezpages.xml?', @configuration_group_id, 10, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'EZPages Footer', 'GOOGLE_SITEMAP_EZPAGES_FOOTER', 'true', 'Use EZPages Footer links to feed sitemapezpages.xml?', @configuration_group_id, 10, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'EZPages changefreq', 'GOOGLE_SITEMAP_EZPAGES_CHANGE_FREQ', 'weekly', 'How frequently the EZPages pages page is likely to change.', @configuration_group_id, 3, NOW(), NULL, 'zen_cfg_select_option(array(\'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\', \'yearly\', \'never\'),'),
(NULL, 'EZPages priority', 'GOOGLE_SITEMAP_EZPAGES_CHANGE_PRIOR', '0.5', 'The default priority of the EZPages URL. Valid values range from 0.0 to 1.0.', @configuration_group_id, 3, NOW(), NULL, NULL);
