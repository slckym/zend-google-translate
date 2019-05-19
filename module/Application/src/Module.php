<?php
	/**
	 * *
	 *     * @link      https://github.com/slckym/zend-google-translate
	 *     * @author Selçuk Yıldırım <selcukyildirim[at]me.com>
	 *
	 */

	namespace Application;

	/**
	 * Class Module
	 * @package Application
	 */
	class Module
	{

		/**
		 *
		 */
		const VERSION = '3.0.3-dev';

		/**
		 * @return mixed
		 */
		public function getConfig()
		{
			return include __DIR__ . '/../config/module.config.php';
		}
	}
