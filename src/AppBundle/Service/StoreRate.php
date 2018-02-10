<?php
namespace AppBundle\Service;

use AppBundle\Entity\Store;
use AppBundle\Entity\User;
use AppBundle\Entity\UserStore;
use Doctrine\ORM\EntityManager;


class StoreRate {
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    public function listsStores(){
        $stores = $this->em->getRepository(Store::class)->findAllStores();
        return $stores;
    }

    public function likedStores(User $user){
        $userStores = $this->em->getRepository(UserStore::class)->findStoresLiked($user);
        return $userStores;
    }

    public function rateStore($action, $store ,User $user){
        $check = $this->checkIsRated($user,$store, $store);
        $store = $this->em->getRepository(Store::class)->find($store);
        if(!$check){
            $userStore = new UserStore();
            $userStore->setRate($action);
            $userStore->setUser($user);
            $userStore->setStore($store);
            $userStore->setCreatedAt(new \DateTime("now"));
            $userStore->setUpdatedAt(new \DateTime("now"));
            $this->em->persist($userStore);
            $this->em->flush();
        }
        else{
            if($check->getRate() == $action){
                $this->em->remove($check);
                $this->em->flush();
            }
            else{
                $check->setRate($action);
                $check->setUpdatedAt(new \DateTime("now"));
                $this->em->persist($check);
                $this->em->flush();
            }
        }
    }

    public function checkIsRated(User $user,$store){
        $rate= $this->em->getRepository('AppBundle:UserStore')
            ->findOneBy(['store' =>$store ,'user' => $user ]);

        if ($rate){
            return $rate;
        }
        else{
            return null;
        }
    }
}
