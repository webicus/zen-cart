Name
====
Google Sitemap

Version Date
==============
v 1.3.13 14.08.2007 10:32

Author
======
Andrew Berezin andrew@ecommerce-service.com http://ecommerce-service.com

Description
===========
This Script generates an Sitemap as described here:
http://www.sitemaps.org/

Affected files
==============
None

Affects DB
==========
Yes (creates new records into configuration_group and configuration tables)

DISCLAIMER
==========
Installation of this contribution is done at your own risk.
Backup your ZenCart database and any and all applicable files before proceeding.

Features:
=========
- supports multilangual categories and products
- supports Search-Engine Safe URLs (osC default)
- could be accessed by http or command line
- generation of sitemap files for products and categories (separate files)
- generation of a sitemap index file
- autogenerates multiple sitemaps for sites with over 50.000 URLs
- autogenerates multiple sitemaps if filesize exceeded 10MB
- writes files compressed or uncompressed
- auto-notify Google, Yahoo!, Ask.com and Microsoft (both live.com and msn.com)
You can use the gzip feature or compress your Sitemap files using gzip.
Please note that your uncompressed Sitemap file may not be larger than 10MB.

Install:
========
1. Unzip and upload all files to your store directory;
2. Go to Admin->Tools->Install SQL Patches and install install.sql.
Copying SQL code from googleanalytics.sql file (using a text editor) and paste into form in your shops
admin at Tools > Install SQL patches (copy & paste, don't upload!!!).
or
Copy install_googlesitemap.php to the admin directory, start it, select Install and press GO button.
3. Go to Admin->Configuration->Google Sitemap Configuration and setup all parameters
4. Go to Admin->Tools>Google XML Sitemap (If error messages occur, change permissions on the XML files to 777);
5. To have this update and automatically notify Google, you will need to set up a Cron
job via your host's control panel.

Tips
====

To run it as a cron job (at 5:0am like you wanted), put something in your crontab like the
following:
0 5 * * * GET 'http://your_domain/googlesitemap.php?ping=yes'
or
0 5 * * * wget -q 'http://your_domain/googlesitemap.php?ping=yes' -O/dev/null
or
0 5 * * * curl -s 'http://your_domain/googlesitemap.php?ping=yes'

You can specify the location of the Sitemap using a robots.txt file. To do this, simply add the following line:
Sitemap: http://<your shop>/sitemapindex.xml
This directive is independent of the user-agent line, so it doesn't matter where you place it in your file.
If you have a Sitemap index file, you can include the location of just that file. You don't need to list
each individual Sitemap listed in the index file.

History
=======
v 1.3.1 18.01.2007 15:05
Changed files: googlesitemap.php
New files: gss.xsl
1. Add Google Sitemaps Stylesheet:
2. Clear some text.
v 1.3.2 02.02.2007 15:51
1. Add parameter for using root directory
v 1.3.3 07.02.2007 8:44
1. Code clean and optimization;
2. Don't use set_time_limit() in SAFE MODE;
3. Remove bla-bla-bla;
4. Add sitemapezpages.xml generation;
v 1.3.4 16.02.2007 0:15
1. Remove parameter GOOGLE_SITEMAP_USE_ROOT_DIRECTORY;
2. Add new parameter - GOOGLE_SITEMAP_XML_FS_DIRECTORY.
v 1.3.5 17.02.2007 0:51
1. Add install_googlesitemap.php for those, who have a problem with SQL-patch.
v 1.3.6 18.02.2007 2:01
1. Don't generate xml file if no output data, but truncate it(!!!!).
2. Include in sitemapindex.xml only files sitemap*.xml with filesize > 0.
v 1.3.6a 03.03.2007 10:55
1. Quick bug fix typo in /admin/googlesitemap.php
v 1.3.6b 19.03.2007 13:13
1. Quick bug fix in install.sql
v 1.3.7 19.03.2007 19:14
1. Add records count statistic
v 1.3.8 28.04.2007 17:14
1. Support new sitemaps.org namespace 0.9.
v 1.3.9 02.05.2007 4:57
1. gss.xls - Support new sitemaps.org namespace 0.9.
2. Remove duplicated links.
v 1.3.10 18.05.2007 12:12
1. Add ez-pages multilanguage support;
2. Fixed filesize error.
v 1.3.11 19.05.2007 2:52
1. Remove non used functions.
v 1.3.12 21.06.2007 12:37
1. Add ping to Yahoo! and Ask.com.
2. Add new parameters:
genxml=no - don't generate xml files;
pinggoogle=yes - ping Google
pingyahoo=yes - ping Yahoo!
pingask=yes - ping Ask.com
3. Fix bug whith SQL-function GREATEST() - add IFNULL.
v 1.3.13 14.08.2007 10:32
1. Add ping to Microsoft (both live.com and msn.com) via www.moreover.com.
2. Add new parameter:
pingms=yes - ping moreover.com
