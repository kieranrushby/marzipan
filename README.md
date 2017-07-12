# marzipan
A very simple flat inline CMS


Installation is quite simple
1. run 'composer install' from the core directory
2. If your site is in a subdirectory change the rewrite base on the .htaccess file to match
3. Browse to the root of your website and enter a username and password
4. Sign in and start editing

CMS methods

Create a content block - Pass in the unique name of the content block to make an editable area
<?php $page->contentBlock(string $name [, array $options]); ?>

Get the page name from the settings
<?php $page->title(); ?>

Get the website name from settings
<?php $page->website_name(); ?>

Page and Global scripts from the settings are included in the header automatically
