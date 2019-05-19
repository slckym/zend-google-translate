<?php
	/**
	 * *
	 *     * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
	 *     * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
	 *     * @license   http://framework.zend.com/license/new-bsd New BSD License
	 *
	 */

	namespace Application\View\Helper;

	/**
	 * Class Highlight
	 * @package Application\View\Helper
	 */
	class Highlight
	{

		/**
		 * @param null $search
		 * @param null $sentence
		 *
		 * @return string|string[]|null
		 */
		public static function make($search = null, $sentence = null)
		{
			$wordsToHighlight = explode(" ", $search);

			return preg_replace('/' . implode('|', $wordsToHighlight) . '/i', '<strong>$0</strong>', $sentence);
		}
	}