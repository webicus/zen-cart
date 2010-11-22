SET @configuration_group_id=0;
SELECT (@configuration_group_id:=configuration_group_id) FROM configuration_group WHERE configuration_group_title= 'Google XML Sitemap' LIMIT 1;

INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES 
(NULL, 'Use Google Sitemaps Stylesheet', 'GOOGLE_SITEMAP_USE_XSL', 'true', 'Google Sitemaps Stylesheet gss.xsl', @configuration_group_id, 6, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Use root directory', 'GOOGLE_SITEMAP_USE_ROOT_DIRECTORY', 'true', 'Use root directory for sitemap files', @configuration_group_id, 7, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),');
