<?php
namespace AppBundle\Security; 

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;


class JwtTokenAuthenticator extends AbstractGuardAuthenticator {
    private $jwtEncoder;
    private $em;
    
    public function __construct(JWTEncoderInterface $jwtEncoder, EntityManager $em) {
        $this->jwtEncoder = $jwtEncoder;
        $this->em = $em;
    }
    
    public function getCredentials(\Symfony\Component\HttpFoundation\Request $request) {
       $extractor = new AuthorizationHeaderTokenExtractor(
            'Bearer',
            'Authorization'
        );
        $token = $extractor->extract($request);
        if (!$token) {
            return;
        }
        return $token; 
    }
    
    public function getUser($credentials, UserProviderInterface $userProvider) {
       try {
        $data = $this->jwtEncoder->decode($credentials);
        } catch (JWTDecodeFailureException $e) {
            throw new CustomUserMessageAuthenticationException('Invalid Token');
        }
        $username = $data['username'];
        return $this->em->getRepository('AppBundle:User')->findOneBy(['username'=> $username]);
    }
    
    public function checkCredentials($credentials, UserInterface $user) {
        return true;
    }

    public function onAuthenticationFailure(\Symfony\Component\HttpFoundation\Request $request, AuthenticationException $exception) {
        //  to do !!
    }

    public function onAuthenticationSuccess(\Symfony\Component\HttpFoundation\Request $request, TokenInterface $token, $providerKey) {
        //  to do !!
    }

    public function supportsRememberMe() {
        return false;
    }
    
    public function start(\Symfony\Component\HttpFoundation\Request $request, AuthenticationException $authException = null) {
        
    }

}
