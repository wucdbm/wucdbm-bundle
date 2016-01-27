<?php

namespace Wucdbm\Bundle\WucdbmBundle\Manager;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginManager extends AbstractManager {

    /**
     * @param AdvancedUserInterface $user
     * @param $area - This is the firewall NAME
     * @param $context - This is the firewall CONTEXT
     */
    public function loginSilent(AdvancedUserInterface $user, $area, $context) {
        $stack = $this->container->get('request_stack');
        $request = $stack->getCurrentRequest();

        if (!$request) {
            return;
        }

        $token = new UsernamePasswordToken($user, $user->getPassword(), $area, $user->getRoles());

        $session = $request->getSession();
        // This one uses the CONTEXT NAME
        $session->set('_security_'.$context, serialize($token));
        // This one uses the FIREWALL NAME
        // This can be used to set the address the user should be redirected to, but is rather useless in this situation?
//        $session->set('_security.'.$area.'.target_path', 'https://website.com/app_dev.php/some/address');
    }

    /**
     * @param AdvancedUserInterface $user
     * @param $area
     */
    public function login(AdvancedUserInterface $user, $area) {
        $stack = $this->container->get('request_stack');
        $request = $stack->getCurrentRequest();

        if (!$request) {
            return;
        }

        // $area is the name of the firewall in your security.yml
        $token = new UsernamePasswordToken($user, $user->getPassword(), $area, $user->getRoles());
        $this->container->get("security.token_storage")->setToken($token);

        // Fire the login event
        // Logging the user in above the way we do it doesn't do this automatically
        $event = new InteractiveLoginEvent($request, $token);
        $this->container->get("event_dispatcher")->dispatch('security.interactive_login', $event);
    }

    public function logout() {
        $stack = $this->container->get('request_stack');
        $request = $stack->getCurrentRequest();

        if (!$request) {
            return;
        }

        $storage = $this->container->get('security.token_storage');
        $token = $storage->getToken();
        $user = $token->getUser();


        if ($user instanceof UserInterface) {
            $storage->setToken(null);
            $session = $request->getSession();
            if ($session instanceof SessionInterface) {
                $session->invalidate();
            }
        }
    }

}