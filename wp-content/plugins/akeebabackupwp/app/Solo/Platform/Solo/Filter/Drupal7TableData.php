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
 * Excludes table data from specific tables
 */
class Drupal7TableData extends FilterBase
{
    public function __construct()
    {
        $this->object = 'dbobject';
        $this->subtype = 'content';
        $this->method = 'direct';
        $this->filter_name = 'Drupal7TableData';

        $configuration = Factory::getConfiguration();

        if ($configuration->get('akeeba.platform.scripttype', 'generic') !== 'drupal7')
        {
            $this->enabled = false;

            return;
        }

        if (Factory::getKettenrad()->getTag() == 'restorepoint')
        {
            $this->enabled = false;

            return;
        }

        // We take advantage of the filter class magic to inject our custom filters
        $this->filter_data['[SITEDB]'] = array(
            '#__cache',
            '#__cache_block',
            '#__cache_bootstrap',
            '#__cache_field',
            '#__cache_filter',
            '#__cache_form',
            '#__cache_image',
            '#__cache_menu',
            '#__cache_page',
            '#__cache_path',
            '#__sessions',
            '#__watchdog',
        );

        parent::__construct();
    }

}
