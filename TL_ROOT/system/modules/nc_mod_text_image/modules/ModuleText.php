<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2012 Leo Feyer
 * 
 * @package   NC Module Text And Image
 * @author    Marcel Mathias Nolte
 * @copyright Marcel Mathias Nolte 2015
 * @website	  https://www.noltecomputer.com
 * @license   <marcel.nolte@noltecomputer.de> wrote this file. As long as you retain this notice you
 *            can do whatever you want with this stuff. If we meet some day, and you think this stuff 
 *            is worth it, you can buy me a beer in return. Meanwhile you can provide a link to my
 *            homepage, if you want, or send me a postcard. Be creative! Marcel Mathias Nolte
 */

namespace NC;


/**
 * Front end content element "text".
 *
 * @package   NC Module Text And Image
 * @author    Marcel Mathias Nolte
 * @copyright Marcel Mathias Nolte 2015
 */
class ModuleNcText extends \Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_nc_text';


	/**
	 * Display a login form
	 *
	 * @return string
	 */
	public function generate()
	{
		return parent::generate();
	}
	
	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		/** @var \PageModel $objPage */
		global $objPage;

		// Clean the RTE output
		if ($objPage->outputFormat == 'xhtml')
		{
			$this->text = \StringUtil::toXhtml($this->text);
		}
		else
		{
			$this->text = \StringUtil::toHtml5($this->text);
		}

		// Add the static files URL to images
		if (TL_FILES_URL != '')
		{
			$path = \Config::get('uploadPath') . '/';
			$this->text = str_replace(' src="' . $path, ' src="' . TL_FILES_URL . $path, $this->text);
		}

		$this->Template->text = \StringUtil::encodeEmail($this->text);
		$this->Template->addImage = false;

		// Add an image
		if ($this->addImage && $this->singleSRC != '')
		{
			$objModel = \FilesModel::findByUuid($this->singleSRC);

			if ($objModel === null)
			{
				if (!\Validator::isUuid($this->singleSRC))
				{
					$this->Template->text = '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
				}
			}
			elseif (is_file(TL_ROOT . '/' . $objModel->path))
			{
				$this->singleSRC = $objModel->path;
				$this->addImageToTemplate($this->Template, $this->arrData);
			}
		}
	}
}
