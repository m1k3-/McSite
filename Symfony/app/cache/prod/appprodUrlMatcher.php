<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * appprodUrlMatcher
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appprodUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    /**
     * Constructor.
     */
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = array();
        $pathinfo = urldecode($pathinfo);

        // minedoc_models_default_index
        if ($pathinfo === '/models') {
            return array (  '_controller' => 'MineDoc\\ModelsBundle\\Controller\\DefaultController::indexAction',  '_route' => 'minedoc_models_default_index',);
        }

        // panel
        if (0 === strpos($pathinfo, '/panel') && preg_match('#^/panel/(?P<pages>[^/]+?)/(?P<orderby>[^/]+?)/(?P<type>[^/]+?)/(?P<search>[^/]+?)$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'MineDoc\\HomeBundle\\Controller\\AdminController::panelAction',)), array('_route' => 'panel'));
        }

        // newsadmin
        if ($pathinfo === '/newsadmin') {
            return array (  '_controller' => 'MineDoc\\HomeBundle\\Controller\\AdminController::newsadminAction',  '_route' => 'newsadmin',);
        }

        // do
        if (0 === strpos($pathinfo, '/do') && preg_match('#^/do/(?P<type>[^/]+?)/(?P<id>[^/]+?)/(?P<new>[^/]+?)$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'MineDoc\\HomeBundle\\Controller\\AdminController::doitAction',)), array('_route' => 'do'));
        }

        // saveitem
        if ($pathinfo === '/saveitem') {
            return array (  '_controller' => 'MineDoc\\HomeBundle\\Controller\\AdminController::saveitemAction',  '_route' => 'saveitem',);
        }

        // game
        if ($pathinfo === '/game') {
            return array (  '_controller' => 'MineDoc\\HomeBundle\\Controller\\DefaultController::gameAction',  '_route' => 'game',);
        }

        // homepage
        if (rtrim($pathinfo, '/') === '') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'homepage');
            }
            return array (  '_controller' => 'MineDoc\\HomeBundle\\Controller\\DefaultController::indexAction',  '_route' => 'homepage',);
        }

        // verify
        if (0 === strpos($pathinfo, '/verify') && preg_match('#^/verify/(?P<mail>[^/]+?)/(?P<key>[^/]+?)$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'MineDoc\\HomeBundle\\Controller\\DefaultController::verifyAction',)), array('_route' => 'verify'));
        }

        // send_command
        if (0 === strpos($pathinfo, '/send') && preg_match('#^/send/(?P<type>[^/]+?)/(?P<name>[^/]+?)$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'MineDoc\\HomeBundle\\Controller\\DefaultController::send_commandAction',)), array('_route' => 'send_command'));
        }

        // chat_get
        if ($pathinfo === '/chat_get') {
            return array (  '_controller' => 'MineDoc\\HomeBundle\\Controller\\DefaultController::chat_getAction',  '_route' => 'chat_get',);
        }

        // chat_send
        if ($pathinfo === '/chat_send') {
            return array (  '_controller' => 'MineDoc\\HomeBundle\\Controller\\DefaultController::chat_sendAction',  '_route' => 'chat_send',);
        }

        // register
        if ($pathinfo === '/register') {
            return array (  '_controller' => 'MineDoc\\HomeBundle\\Controller\\DefaultController::registerAction',  '_route' => 'register',);
        }

        // login
        if ($pathinfo === '/login') {
            return array (  '_controller' => 'MineDoc\\HomeBundle\\Controller\\DefaultController::loginAction',  '_route' => 'login',);
        }

        // logout
        if ($pathinfo === '/logout') {
            return array (  '_controller' => 'MineDoc\\HomeBundle\\Controller\\DefaultController::logoutAction',  '_route' => 'logout',);
        }

        // dons
        if ($pathinfo === '/dons') {
            return array (  '_controller' => 'MineDoc\\HomeBundle\\Controller\\DefaultController::donateAction',  '_route' => 'dons',);
        }

        // shop
        if ($pathinfo === '/shop') {
            return array (  '_controller' => 'MineDoc\\HomeBundle\\Controller\\DefaultController::shopAction',  '_route' => 'shop',);
        }

        // buy
        if (0 === strpos($pathinfo, '/buy') && preg_match('#^/buy/(?P<id>[^/]+?)/(?P<nbr>[^/]+?)$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'MineDoc\\HomeBundle\\Controller\\DefaultController::buyAction',)), array('_route' => 'buy'));
        }

        // dynmap
        if ($pathinfo === '/dynmap') {
            return array (  '_controller' => 'MineDoc\\HomeBundle\\Controller\\DefaultController::dynmapAction',  '_route' => 'dynmap',);
        }

        // minedoc_layout_default_index
        if (0 === strpos($pathinfo, '/hello') && preg_match('#^/hello/(?P<name>[^/]+?)$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'MineDoc\\LayoutBundle\\Controller\\DefaultController::indexAction',)), array('_route' => 'minedoc_layout_default_index'));
        }

        // minedoc_chat_default_index
        if (0 === strpos($pathinfo, '/hello') && preg_match('#^/hello/(?P<name>[^/]+?)$#s', $pathinfo, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'MineDoc\\ChatBundle\\Controller\\DefaultController::indexAction',)), array('_route' => 'minedoc_chat_default_index'));
        }

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}
