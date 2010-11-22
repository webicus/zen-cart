DELETE FROM configuration WHERE configuration_key = 'GOOGLE_SITEMAP_USE_ROOT_DIRECTORY' LIMIT 1;

SET @configuration_group_id=0;
SELECT (@configuration_group_id:=configuration_group_id) FROM configuration_group WHERE configuration_group_title= 'Google XML Sitemap' LIMIT 1;

INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES 
(NULL, 'XML directory', 'GOOGLE_SITEMAP_XML_FS_DIRECTORY', '', 'Directory using for saving XML files. Setting it to your root directory. If empty, Google Sitemap use DIR_FS_CATALOG directory.', @configuration_group_id, 7, NOW(), NULL, NULL);
