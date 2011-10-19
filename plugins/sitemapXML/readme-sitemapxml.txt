Name
====
SitemapXML

Version Date
==============
v 3.0.2 11.08.2011 16:14

Author
======
Andrew Berezin http://eCommerce-Service.com

Description
===========
This Script generates an Sitemap as described here:
http://www.sitemaps.org/

Support thread
==============
http://www.zen-cart.com/forum/showthread.php?p=725347

Affected files
==============
None

Affects DB
==========
Yes (creates new records into configuration_group, configuration and admin_pages tables).

DISCLAIMER
==========
Installation of this contribution is done at your own risk.
Backup your ZenCart database and any and all applicable files before proceeding.

Features:
=========
- supports multilangual products
- supports multilangual categories
- supports Search-Engine Safe URLs
- could be accessed by http or command line
- autogenerates multiple sitemaps for sites with over 50.000 URLs
- autogenerates multiple sitemaps if filesize exceeded 10MB
- writes files compressed or uncompressed
- using index.php wrapper - http://domain.com/index.php?main_page=sitemapxml
- using languages file and etc.
- auto-notify Google, Yahoo!, Ask.com and Microsoft (both live.com and msn.com)
You can use the gzip feature or compress your Sitemap files using gzip.
Please note that your uncompressed Sitemap file may not be larger than 10MB.
- generation of a sitemap index file
- generation of xml-sitemaps for (separate files):
1. Products (support hideCategories),
2. Categories (support hideCategories),
3. Manufacturers,
4. Home page,
5. Reviews,
6. EZ-pages
- multi-language support,
- 'EZ pages rel=nofollow attribute' support (http://www.zen-cart.com/index.php?main_page=product_contrib_info&products_id=944),
- 'date_added'/'last_modified' support,
- check internal links ('alt_url') by "noindex" rule (main_page in ROBOTS_PAGES_TO_SKIP),
- toc_chapter proccessing
7. Testimonials,

If the products, categories, reviews have not changed since the last generation (time creation corresponding xml-sitemap file), a new xml-sitemap file not created (using existing xml-sitemap).

Priority is calculated on the basis of the positions in the selection from the database, ie the operator ORDER BY in the sql query. First item have priority=1.00, last=0.10. So can no longer be situations where all items in the file have the same priority.
Products - ORDER BY p.products_sort_order ASC, last_date DESC
Categories - ORDER BY c.sort_order ASC, last_date DESC
Reviews - ORDER BY r.reviews_rating ASC, last_date DESC
EZ-pages - ORDER BY p.sidebox_sort_order ASC, last_date DESC
Testimonials - ORDER BY last_date DESC

Support 3 $_GET parameters:
ping=yes - Pinging Google, Yahoo!, Ask.com and Microsoft.

inline=yes - output file sitemapindex.xml. In Google Webmaster Tools you can define your "Sitemap URL":
http://your_domain/index.php?main_page=sitemapxml&inline=yes
And every time Google will get index.php?main_page=sitemapxml he will receive a fresh sitemapindex.xml.

genxml=no - don't generate xml-files.

rebuild=yes - force rebuild all sitemap*.xml files.

Comments and suggestions are welcome.
If you need any more sitemaps (faq, news, etc) you may ask me, but I will do only if it matches with my interests.

Install:
========
0. Backup all Zen-Cart files to your server or local computer.
   Backup your database.
1. Unzip and upload all files to your store directory.
2. For Zen-Cart 1.5.x. Run install-sitemapxml.sql
3. Go to Admin->Tools->Sitemap XML and click "Install SitemapXML SQL".
4. Go to Admin->Configuration->Sitemap XML and setup all parameters.
5. Go to Admin->Tools->Google XML Sitemap (If error messages occur, change permissions on the XML files to 777).
6. To have this update and automatically notify Google, you will need to set up a Cron job via your host's control panel.

Upgrade:
========
0. Backup all Zen-Cart files to your server or local computer.
   Backup your database.
1. Unzip and upload all files to your store directory.
2. Go to Admin->Tools->Sitemap XML and click "Update SitemapXML SQL".
3. Go to Admin->Configuration->Sitemap XML and setup all parameters.
4. Go to Admin->Tools->Google XML Sitemap (If error messages occur, change permissions on the XML files to 777).

Un-Install:
========
1. Go to Admin->Tools->Sitemap XML and click "Un-Install SitemapXML SQL".
2. Delete all files that were copied from the installation package.

Tips
====
To run it as a cron job (at 5:0am like you wanted), put something in your crontab like the following:
0 5 * * * GET 'http://your_domain/index.php?main_page=sitemapxml'
or
0 5 * * * wget -q 'http://your_domain/index.php?main_page=sitemapxml' -O/dev/null
or
0 5 * * * curl -s 'http://your_domain/index.php?main_page=sitemapxml'
or
0 5 * * * php -f <path to shop>/cgi-bin/sitemapxml.php rebuild=yes

You can specify the location of the Sitemap using a robots.txt file. To do this, simply add the following line:
Sitemap: http://<your shop>/sitemapindex.xml
This directive is independent of the user-agent line, so it doesn't matter where you place it in your file.
If you have a Sitemap index file, you can include the location of just that file. You don't need to list
each individual Sitemap listed in the index file.

History
=======
v 2.0.0 02.02.2009 19:21 - Initial version
v 2.1.0 30.04.2009 10:35 - Lot of changes and bug fixed
v 3.0.2 11.08.2011 16:14 - Lot of changes and bug fixed, Zen-Cart 1.5.0 Support, MagicSeo Support
v 3.0.3 27.08.2011 13:11 - Small bug fix, delete Zen-Cart 1.5.0 Autoinstall