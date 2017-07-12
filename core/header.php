<?php defined('MRZPN_EXECUTE') or die('Access Denied.'); ?>
<?php $page = new page(); ?>

<?php if($page->hasLogin()): ?>
<link rel="stylesheet" type="text/css" href="<?= WEBROOT ?>/core/src/content_tools/content-tools.min.css" />
<link rel="stylesheet" type="text/css" href="<?= WEBROOT ?>/core/css/core-menu.css" />

<script src="<?= WEBROOT ?>/core/src/content_tools/content-tools.js"></script>
<script src="<?= WEBROOT ?>/core/js/content-tools_editor.js"></script>
<script src="<?= WEBROOT ?>/core/js/core-menu.js"></script>
<?php endif; ?>

<script type="text/javascript" id="mrzpn-p-script"><?= $page->page_script(); ?></script>
<script type="text/javascript" id="mrzpn-g-script"><?= $page->global_script(); ?></script>
