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
 * Subdirectories exclusion filter. Excludes temporary, cache and backup output
 * directories' contents from being backed up.
 */
class ExcludeFiles extends FilterBase
{
	public function __construct()
	{
		$this->object = 'file';
		$this->subtype = 'all';
		$this->method = 'direct';
		$this->filter_name = 'ExcludeFiles';

		if (Factory::getKettenrad()->getTag() == 'restorepoint')
		{
			$this->enabled = false;
		}

		// Get the site's root
		$configuration = Factory::getConfiguration();

		$root = $configuration->get('akeeba.platform.newroot', '[SITEROOT]');

		// We take advantage of the filter class magic to inject our custom filters
		$this->filter_data[$root] = array(
			'kickstart.php',
			'error_log',
		);

		parent::__construct();
	}

}
