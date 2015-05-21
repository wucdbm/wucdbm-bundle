<?php

namespace Wucdbm\Bundle\WucdbmBundle\Manager;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginManager extends AbstractManager {

    /**
     * @param AdvancedUserInterface $user
     * @param $area
     */
    public function login(AdvancedUserInterface $user, $area) {
        $request = $this->container->get('request');

        // $area is the name of the firewall in your security.yml
        $token = new UsernamePasswordToken($user, $user->getPassword(), $area, $user->getRoles());
        $this->container->get("security.token_storage")->setToken($token);

        // Fire the login event
        // Logging the user in above the way we do it doesn't do this automatically
        $event = new InteractiveLoginEvent($request, $token);
        $this->container->get("event_dispatcher")->dispatch('security.interactive_login', $event);
    }

    public function logout() {
//        The service security.context is deprecated along with the above change. Recommended to use instead:
//
//        @security.authorization_checker => isGranted()
//        @security.token_storage         => getToken()
//        @security.token_storage         => setToken()
//        $request = $this->container->get('security.authorization_checker');
        $request = $this->container->get('request');
        $storage = $this->container->get('security.token_storage');
        $token   = $storage->getToken();
        $user    = $token->getUser();

        $session = $request->getSession();

        if ($user instanceof AdvancedUserInterface) {
            $storage->setToken(null);
            if ($session instanceof SessionInterface) {
                $session->invalidate();
            }
        }
    }

}