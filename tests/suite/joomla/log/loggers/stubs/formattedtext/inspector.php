<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Log
 *
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Inspector classes for the JLog package.
 */

/**
 * @package		Joomla.UnitTest
 * @subpackage	Log
 */
class JLoggerFormattedTextInspector extends JLoggerFormattedText
{
	public $file;
	public $format = '{DATETIME}	{PRIORITY}	{CATEGORY}	{MESSAGE}';
	public $options;
	public $fields;
	public $path;
}