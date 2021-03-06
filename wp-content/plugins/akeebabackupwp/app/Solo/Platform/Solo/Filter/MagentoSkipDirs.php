<?php
/**
 * @package    solo
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

namespace Akeeba\Engine\Filter;

use Akeeba\Engine\Factory;
use Akeeba\Engine\Filter\Base as FilterBase;

// Protection against direct access
defined('AKEEBAENGINE') or die();

/**
 * Magento specific Filter: Skip Directories
 *
 * Exclude files of special directories
 */
class MagentoSkipDirs extends FilterBase
{
	public function __construct()
	{
        parent::__construct();

		$this->object	= 'dir';
		$this->subtype	= 'children';
		$this->method	= 'direct';
		$this->filter_name = 'MagentoSkipDirs';

		$configuration = Factory::getConfiguration();

		if ($configuration->get('akeeba.platform.scripttype', 'generic') !== 'magento')
		{
			$this->enabled = false;

			return;
		}

		$root = $configuration->get('akeeba.platform.newroot', '[SITEROOT]');

		// Exclude directories
		$this->filter_data[$root] = array (
			// catalog images cache
			'media/catalog/product/cache',
			// images ready to be imported
			'media/import',
			// exported images
			'media/export',
			// built-in magento backups
			'var/backups',
			// temp directory
			'var/tmp',
			// All cacheable objects except the page cache
			'var/cache',
			// session directory
			'var/session',
			// The logs directory
			'var/log',
			// Magento Connect tmp folder
			'var/package/tmp',

            // Magento 2 directories
            // Info taken from this page: http://devdocs.magento.com/guides/v2.0/howdoi/php/php_clear-dirs.html
            // Contains generated code
            'var/generation',
            // Composer cache
            'var/composer_home',
            // Contains the compiled dependency injection configuration for all modules.
            'var/di',
            // Cached pages from the full page cache mechanism.
            'var/page_cache',
            // Minified templates and compiled LESS (meaning LESS, CSS, and HTML).
            'var/view_preprocessed',
            // Products images cache
            'pub/media/catalog/product/cache',
            // images ready to be imported
            'pub/media/import',
            // static resources
            'pub/static/adminhtml',
            'pub/static/frontend',
		);
	}
}
