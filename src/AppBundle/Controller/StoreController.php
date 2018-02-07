<?php
/**
 * Created by PhpStorm.
 * User: nacer
 * Date: 05/02/18
 * Time: 11:51
 */
namespace AppBundle\Controller;

use AppBundle\Entity\Store;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\UserStore;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


/**
 * Class StoreController
 * @package AppBundle\Controller
 * @Route("/api")
 */
class StoreController extends Controller{

    /**
     * Lists of all Stores
     * @Route("/stores", name="Store_index")
     */
    public function listStoresAction(){
        $stores = $this->get('store.rate')->listsStores();
        $data = $this->get('jms_serializer')->serialize($stores, 'json');
        $response = new Response($data, 201);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/favoris", name="favoris")
     * @Method("GET")
     */
    public function favorisAction(){
        $user = $this->getUser();
        $userStores = $this->get('store.rate')->likedStores($user);
        $data = $this->get('jms_serializer')->serialize($userStores, 'json');
        $response = new Response($data, 201);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/rate", name="rate")
     * @Method({"DELETE", "POST", "PUT"})
     */
    public function rateAction(Request $request){
        $str = $request->getContent(); //data as a String Format
        $data = json_decode( $str,true); //data as Array
        $rate = $this->get('jms_serializer')->deserialize($str, 'AppBundle\Entity\UserStore', 'json');
        //var_dump($rate); die;
        $action = $data['rate'];
        $user = $this->getUser();
        $service = $this->get('store.rate')->rateStore($action, $rate->getStore(), $user);

        return 'khdeeemt !! ';
    }



}