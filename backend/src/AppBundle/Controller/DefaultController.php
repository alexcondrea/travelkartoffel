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
        $content = file_get_contents(realpath($this->container->getParameter('kernel.root_dir') . '/../../frontend/index.html'));
        return new Response($content);
    }

    /**
     * @Route("/build/{path}", requirements={"path"=".+"})
     */
    public function pathAction($path)
    {
        $file = $this->container->getParameter('kernel.root_dir') . '/../../frontend/build/' . $path;
        if(!is_file($file)) {
            throw $this->createNotFoundException('file not found ' . $file);
        }

        $content = file_get_contents($file);

        return new Response($content);

    }

        /**
     * @Route("/routes", name="trivago_kartoffel_routes")
     */
    public function routesAction(Request $request)
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
