<?php
	/**
	 * *
	 *     * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
	 *     * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
	 *     * @license   http://framework.zend.com/license/new-bsd New BSD License
	 *
	 */

	namespace Application\Controller;

	use Application\Enums\HttpMethods;
	use Zend\Http\Client;
	use Zend\Http\Request;
	use Zend\Mvc\Controller\AbstractActionController;
	use Zend\Stdlib\Parameters;
	use Zend\View\Model\ViewModel;

	/**
	 * Class IndexController
	 * @package Application\Controller
	 */
	class IndexController extends AbstractActionController
	{

		/**
		 * @var
		 */
		protected $apiKey;

		/**
		 * @var
		 */
		protected $translateUrl;

		/**
		 * IndexController constructor.
		 */
		public function __construct()
		{
			$this->apiKey       = "AIzaSyBabSGLuzycUB-4wqdP8iY2hBOnPPMrb38";
			$this->translateUrl = "https://translation.googleapis.com/language/translate/v2/";
		}

		/**
		 * @return \Zend\View\Model\ViewModel
		 */
		public function indexAction()
		{
			$values = null;
			if ($this->getRequest()->getMethod() == "POST") {
				$text     = $this->params()->fromPost('text');
				$target   = $this->params()->fromPost('target');
				$words    = $this->params()->fromPost('words');
				$language = $this->params()->fromPost('from');

				if ($language == 'detect') {
					$data      = $this->request($this->translateUrl . "detect", new Parameters([
						'key' => $this->apiKey,
						'q'   => $text
					]));
					$detection = $data['data']['detections'];
					$language  = $detection[0][0]['language'];
				}

				$translations = $this->request($this->translateUrl, new Parameters([
					'key'    => $this->apiKey,
					'source' => $language,
					'target' => $target,
					'q'      => $text
				]));
			}

			$data = $this->request($this->translateUrl . "languages", new Parameters([
				'key'    => $this->apiKey,
				'target' => 'en'
			]));

			$values = [
				'source'       => isset($language) ? $language : null,
				'target'       => isset($target) ? $target : \Locale::getDefault(),
				'translations' => isset($translations) ? $translations['data']['translations'] : [],
				'languages'    => $data['data']['languages'],
				'text'         => isset($text) ? $text : null,
				'words'        => isset($words) ? $words : null
			];

			return new ViewModel($values);
		}

		public
		function request(string $url, Parameters $parameters)
		{
			$request = new Request();
			$request->getHeaders()->addHeaders([
				'Content-Type' => 'application/json; charset=UTF-8'
			]);
			$request->setUri($url);
			$request->setMethod(HttpMethods::GET);

			$request->setQuery($parameters);

			$client   = new Client();
			$response = $client->dispatch($request);
			$data     = json_decode($response->getBody(), true);

			return $data;
		}
	}
