SET @configuration_group_id=0;
SELECT @configuration_group_id:=configuration_group_id
FROM configuration_group
WHERE configuration_group_title= 'Google Base Feeder Configuration'
LIMIT 1;
DELETE FROM configuration WHERE configuration_group_id = @configuration_group_id;
DELETE FROM configuration_group WHERE configuration_group_id = @configuration_group_id;


#REMOVE #'s to remove UPC/ISBN support:

#ALTER TABLE products DROP COLUMN products_upc;
#ALTER TABLE products DROP COLUMN products_isbn;
