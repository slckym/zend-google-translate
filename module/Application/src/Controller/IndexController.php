<?php
	/**
	 * *
	 *     * @link      https://github.com/slckym/zend-google-translate
	 *     * @author Selçuk Yıldırım <selcukyildirim[at]me.com>
	 *
	 */

	namespace Application\Controller;

	use Application\Enums\HttpMethods;
	use Application\Module;
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
			$module = new Module();
			$config = $module->getConfig();

			$this->apiKey       = $config['translate']['apiKey'];
			$this->translateUrl = $config['translate']['baseUrl'];
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
