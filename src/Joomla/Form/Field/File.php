<?php
/**
 * Part of the Joomla Framework Form Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form;

/**
 * Form Field class for the Joomla Framework.
 * Provides an input field for files
 *
 * @link   http://www.w3.org/TR/html-markup/input.file.html#input.file
 * @since  1.0
 */
class Field_File extends Field
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $type = 'File';

	public function processSave() {
		if (!$this->value || !$this->value['name']) {
			return null;
		}

		$value = $this->value;
		$target_dir = $this->element['target'] ? JPATH_ROOT . '/' . trim($this->element['target'], '/\\') : JPATH_ROOT;

		// Undefined | Multiple Files | $_FILES Corruption Attack
	    // If this request falls under any of them, treat it invalid.
	    if (
	        !isset($value['error']) ||
	        is_array($value['error'])
	    ) {
	        throw new \RuntimeException('Invalid file upload parameters.');
	    }

	    // Check $value['error'] value.
	    switch ($value['error']) {
	        case UPLOAD_ERR_OK:
	            break;
	        case UPLOAD_ERR_NO_FILE:
	            throw new \RuntimeException('No file sent.');
	        case UPLOAD_ERR_INI_SIZE:
	        case UPLOAD_ERR_FORM_SIZE:
	            throw new \RuntimeException('Exceeded filesize limit.');
	        default:
	            throw new \RuntimeException('Unknown errors.');
	    }

	    // You should also check filesize here.
	    if ($value['size'] > 268435456) {
	        throw new \RuntimeException('Exceeded arbitrary filesize limit.');
	    }

	    // DO NOT TRUST $value['mime'] VALUE !!
	    // Check MIME Type by yourself.
	    // $finfo = new \finfo(FILEINFO_MIME_TYPE);
	    // if (false === $ext = array_search(
	    //     $finfo->file($value['tmp_name']),
	    //     array(
	    //         'jpg' => 'image/jpeg',
	    //         'png' => 'image/png',
	    //         'gif' => 'image/gif',
		// 		'mp4' => 'video/mp4'
	    //     ),
	    //     true
	    // )) {
	    //     throw new \RuntimeException('Invalid file format.');
	    // }

		if (!file_exists($target_dir)) {
			mkdir($target_dir);
		}

		$ext = strtolower(pathinfo($value['name'], PATHINFO_EXTENSION));

		$file_location = sprintf($target_dir.'/%s.%s',
			sha1_file($value['tmp_name']),
			$ext
		);

	    // You should name it uniquely.
	    // DO NOT USE $value['name'] WITHOUT ANY VALIDATION !!
	    // On this example, obtain safe unique name from its binary data.
	    if (!move_uploaded_file(
	        $value['tmp_name'],
	        $file_location
	    )) {
	        throw new \RuntimeException('Failed to move uploaded file.');
	    }

	    return $file_location;
	}

	/**
	 * Method to get the field input markup for the file field.
	 * Field attributes allow specification of a maximum file size and a string
	 * of accepted file extensions.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.0
	 *
	 * @note    The field does not include an upload mechanism.
	 * @see     JFormFieldMedia
	 */
	protected function getInput()
	{
		// Initialize some field attributes.
		$accept = $this->element['accept'] ? ' accept="' . (string) $this->element['accept'] . '"' : '';
		$size = $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';

		// Initialize JavaScript field attributes.
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		return '<input type="file" name="' . $this->name . '" id="' . $this->id . '"' . ' value=""' . $accept . $disabled . $class . $size
			. $onchange . ' />';
	}
}
