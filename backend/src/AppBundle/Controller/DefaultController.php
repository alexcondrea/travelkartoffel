<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="trivago_kartoffel_homepage")
     */
    public function indexAction(Request $request)
    {
        $routes = [];
        foreach($this->get('router')->getRouteCollection() as $name => $route) {
            /** @var $route \Symfony\Component\Routing\Route */
            if(strpos($name, 'trivago') === false && strpos($name, 'karto') === false) {
                continue;
            }

            $routes[] = json_decode($this->get('serializer')->serialize($route, 'json'), true);
        }

        return new Response('<pre>' . nl2br(json_encode($routes, JSON_PRETTY_PRINT)) . '</pre>');
    }
}
