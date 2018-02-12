<?php
/**
 * Created by PhpStorm.
 * User: nacer
 * Date: 09/02/18
 * Time: 23:51
 */

namespace AppBundle\Test;
use AppBundle\Entity\UserStore;
use Doctrine\ORM\EntityManager;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use Psr\Http\Message\RequestInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Helper\FormatterHelper;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;


use Symfony\Component\DomCrawler\Crawler;


class ApiTestCase extends KernelTestCase {
  private static $staticClient;

  /**
   * @var array
   */
  private static $history = array();

  /**
   * @var Client
   */
  protected $client;

  /**
   * @var ConsoleOutput
   */
  private $output;

  /**
   * @var FormatterHelper
   */
  private $formatterHelper;

  private $responseAsserter;

  public static function setUpBeforeClass(){
    $handler = HandlerStack::create();

    $handler->push(Middleware::history(self::$history));
    $handler->push(Middleware::mapRequest(
      function(RequestInterface $request){
        $path = $request->getUri()->getPath();
        if(strpos($path, '/app_test.php') !== 0 ){
          $path = '/app_test.php'.$path;
        }
        $uri = $request->getUri()->withPath($path);

        return $request->withUri($uri);
      }
    ));
    $baseUrl = getenv('TEST_BASEURL');
    if(!$baseUrl){
      static::fail('No TEST_BASE_URL environmental variable set in phpunit.xml.');
    }

    self::$staticClient = new Client([
      'base_url' => 'http://localhost:8000',
      'default' => [
        'exceptions' => false,
      ]
    ]);
    self::bootKernel();
  }

  protected function setup(){
    $this->client = self::$staticClient;
    self::$history = array();
    $this->purgeDatabase();
  }

  /**
   * Clean up kernel usage in this test !
   */
  protected function tearDown()
  {
    // purposefully not calling parent class, which shuts down the kernel
  }

  protected function onNotSuccessfulTest(Exception $e){
    if($lastResponse = $this->getLastResponse()){
      $this->printDebug('');
      $this->printDebug('<error>Failure!</error> when making the following request:');
      $this->printLastRequestUrl();
      $this->printDebug('');
      $this->debugResponse($lastResponse);
    }
    throw $e;
  }


  private function purgeDatabase(){
    $purger = new ORMPurger($this->getService('doctrine')->getManager());
    $purger->purge();
  }

  protected function getService($id){
    return self::$kernel->getContainer()->get($id);
  }

  protected function printLastRequestUrl(){
    $lastRequest = $this->getLastRequest();
    if($lastRequest){
      $this->printDebug(sprintf('
      <comment>%s</comment>: <info>%s</info>',
        $lastRequest->getMethod(), $lastRequest->getUri()
        ));
    } else{
      $this->printDebug('No request was made.');
    }
  }

  protected function debugResponse(RequestInterface $response){
    foreach ($response->getHeaders() as $name => $values){
      $this->printDebug(sprintf(
        '%s: %s', $name, implode(', ',$values)
      ));
    }
    $body = (string) $response->getBody();

    $contentType = $response->getHeader('Content-Type');
    $contentType = $contentType[0];

    if($contentType == 'application/json' || strops($contentType, '+json') !== false){
      $data = json_decode($body);
      if($data === nul){
        $this->printDebug($body);
      } else{
        $this->printDebug(json_encode($data, JSON_PRETTY_PRINT));
      }
    } else{
      $isValideHtml = strops($body, '</body>') !== false;
      if($isValideHtml){
        $this->printDebug('');
        $crawler = new Crawler($body);

        // Specific to symfony's error page
        $isError = $crawler->filter('#traces-0')->count() > 0 || strpos($body);
        if($isError){
          $this->printDebug('There was An Error!! ');
          $this->printDebug('');
        }else{
          $this->printDebug('HTML Summary h1 and h2 ');
        }

        //finds the h1 and h2 tags and prints them !
        foreach ( $crawler->filter('h1, h2') as $header) {
          //Avoid these meaningless headers
          if(strpos($header, 'Stack Trace') !== false){
            continue;
          }
          if (strpos($header, 'Logs') !== false) {
            continue;
          }
          // Remove line breaks
          $header = str_replace("\n", ' ', trim($header));
          // trim any excess whitespace
          $header = preg_replace('/(\s)+/', ' ', $header);

          if($isError){
            $this->printErrorBlock($header);
          }else{
            $this->printDebug($header);
          }
        }
        /*
         * Turn on temporarily the profiler in the config_test.yml file
         */

        $profileUrl = $response->getHeader('X-Debug-Token-Link');
        if($profileUrl){
          $fullProfilerUrl = $response->getHeader('Host')[0].$profileUrl[0];
          $this->printDebug('');
          $this->printDebug(sprintf('Profiler URL: <comment>%s</comment>',$fullProfilerUrl));
        }
        $this->printDebug(''); //just for spacing
      }else {
        $this->printDebug($body);
      }
    }

  }

  /**
   * Print a message out - useful for debugging
   *
   * @param $string
   */
  protected function printDebug($string){
    if ($this->output === null) {
      $this->output = new ConsoleOutput();
    }

    $this->output->writeln($string);
  }

  /**
   * Print a debugging message out in a big red block
   *
   * @param $string
   */
  protected function printErrorBlock($string)
  {
    if ($this->formatterHelper === null) {
      $this->formatterHelper = new FormatterHelper();
    }
    $output = $this->formatterHelper->formatBlock($string, 'bg=red;fg=white', true);

    $this->printDebug($output);
  }

  /**
   * @return RequestInterface
   */
  private function getLastRequest(){
    if (!self::$history || empty(self::$history)) {
      return null;
    }

    $history = self::$history;

    $last = array_pop($history);

    return $last['request'];
  }

  /**
   * @return ResponseInterface
   */
  private function getLastResponse()
  {
    if (!self::$history || empty(self::$history)) {
      return null;
    }

    $history = self::$history;

    $last = array_pop($history);

    return $last['response'];
  }

  protected function createUser($username, $plainPassword = 'password'){
    $user = new User();
    $user->setUsername($username);
    $user->setEmail($username.'@hiddenfounders.com');
    $password = $this->getService('security.password_encoder')
      ->encodePassword($user, $plainPassword);
    $user->setPassword($password);
    $em = $this->getEntityManager();
    $em->persist($user);
    $em->flush();
    return $user;
  }

  protected function getAuthorizedHeaders($username, $headers = array()){
    $token = $this->getService('lexik_jwt_authentication.encoder')
      ->encode(['username'=>$username]);
    $herders['Authorization'] = 'Bearer '.$token;
    return $headers;
  }

  protected function createRate(array $data){
    $data = array_merge(array(
      'rate' => 1,
      'store'=> 4,
      'user' => $this->getEntityManager()
        ->getRepository('AppBundle:User')
        ->findAny()
    ), $data);
    $accessor = PropertyAccess::createPropertyAccessor();

    $rate = new UserStore();
    foreach($data as $key => $value){
      $accessor->setValue($rate, $key, $value);
    }
    $this->getEntityManager()->persist($rate);
    $this->getEntityManager()->flush();

    return $rate;
  }

  /**
   * @return EntityManager
   */
  protected function getEntityManager()
  {
    return $this->getService('doctrine.orm.entity_manager');
  }

  /**
   * @return ResponseAsseter
   */
  protected function asserter(){
    if($this->responseAsserter === null){
      $this->reponserAsserter = new ResponseAsserter();
    }
    return $this->responseAsserter;
  }

  /**
   * Call this when you want to compare URLs in a test
   *
   * (since the returned URL's will have /app_test.php in front)
   *
   * @param string $uri
   * @return string
   */
  protected function adjustUri($uri)
  {
    return '/app_test.php'.$uri;
  }
}
