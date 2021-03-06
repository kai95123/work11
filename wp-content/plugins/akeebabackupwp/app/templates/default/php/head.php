<?php
/**
 * @package    solo
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

use Awf\Document\Document;
use Awf\Uri\Uri;

/** @var Document $this */

$scripts = $this->getScripts();
$scriptDeclarations = $this->getScriptDeclarations();
$styles = $this->getStyles();
$styleDeclarations = $this->getStyleDeclarations();

$darkMode = $this->getContainer()->appConfig->get('darkmode', 0);

// Scripts before the template ones
if(!empty($scripts)) foreach($scripts as $url => $params)
{
	if($params['before'])
	{
		echo "\t<script type=\"{$params['mime']}\" src=\"$url\"></script>\n";
	}
}

// Template scripts
?>
<script type="text/javascript" src="<?php echo Uri::base(); ?>media/js/jquery.min.js?<?php echo $this->container->mediaQueryKey ?>"></script>
<script type="text/javascript" src="<?php echo Uri::base(); ?>media/js/jquery-migrate.min.js?<?php echo $this->container->mediaQueryKey ?>"></script>
<script type="text/javascript">
    if (typeof akeeba == 'undefined') { var akeeba = {}; }
    if (typeof akeeba.loadScripts == 'undefined') { akeeba.loadScripts = []; }
</script>
<script type="text/javascript" src="<?php echo Uri::base(); ?>media/js/fef/menu.min.js?<?php echo $this->container->mediaQueryKey ?>"></script>
<script type="text/javascript" src="<?php echo Uri::base(); ?>media/js/fef/tabs.min.js?<?php echo $this->container->mediaQueryKey ?>"></script>
<script type="text/javascript">window.addEventListener('DOMContentLoaded', function(event) {
	akeeba.fef.menuButton();
	akeeba.fef.tabs();
<?php if ($darkMode): ?>
	akeeba.fef.darkMode(true);
<?php endif; ?>
});</script>
<?php
// Scripts after the template ones
if(!empty($scripts)) foreach($scripts as $url => $params)
{
	if(!$params['before'])
	{
		echo "\t<script type=\"{$params['mime']}\" src=\"$url\"></script>\n";
	}
}

// Script declarations
if(!empty($scriptDeclarations)) foreach($scriptDeclarations as $type => $content)
{
	echo "\t<script type=\"$type\">\n$content\n</script>";
}

// CSS files before the template CSS
if(!empty($styles)) foreach($styles as $url => $params)
{
	if($params['before'])
	{
		$media = ($params['media']) ? "media=\"{$params['media']}\"" : '';
		echo "\t<link rel=\"stylesheet\" type=\"{$params['mime']}\" href=\"$url\" $media></script>\n";
	}
}
?>
<link rel="stylesheet" type="text/css" href="<?php echo Uri::base(); ?>/media/css/fef.min.css?<?php echo $this->container->mediaQueryKey ?>" />
<?php if ($darkMode): ?>
<link rel="stylesheet" type="text/css" href="<?php echo Uri::base(); ?>/media/css/dark.min.css?<?php echo $this->container->mediaQueryKey ?>" />
<?php endif; ?>
<?php if (defined('AKEEBADEBUG') && AKEEBADEBUG && @file_exists(APATH_BASE . '/media/css/theme.css')): ?>
<link rel="stylesheet" type="text/css" href="<?php echo \Awf\Uri\Uri::base(); ?>/media/css/theme.css?<?php echo $this->container->mediaQueryKey ?>" />
<?php else: ?>
<link rel="stylesheet" type="text/css" href="<?php echo \Awf\Uri\Uri::base(); ?>/media/css/theme.min.css?<?php echo $this->container->mediaQueryKey ?>" />
<?php endif; ?>
<?php
// CSS files before the template CSS
if(!empty($styles)) foreach($styles as $url => $params)
{
	if(!$params['before'])
	{
		$media = ($params['media']) ? "media=\"{$params['media']}\"" : '';
		echo "\t<link rel=\"stylesheet\" type=\"{$params['mime']}\" href=\"$url\" $media></script>\n";
	}
}

// Script declarations
if(!empty($styleDeclarations)) foreach($styleDeclarations as $type => $content)
{
	echo "\t<style type=\"$type\">\n$content\n</style>";
}
