<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PriceController extends Controller
{
    /**
     * @Route("/kartoffel/api/price")
     */
    public function indexAction()
    {
        $price = $this->get('trivago.price_calculator')->getHotelPriceForRange('Berlin', new \DateTime(), 5);

        echo $price; exit;
    }

    /**
     * @Route("/kartoffel/api/storage/{id}")
     */
    public function detailsAction($id)
    {
        foreach($this->getStorage() as $storage) {
            if($storage['id'] == $id) {
                return $this->json($storage);
            }
        }

        throw $this->createNotFoundException('Element not found');
    }

    /**
     * @return array
     */
    private function getStorage()
    {
        return json_decode(file_get_contents(dirname(__DIR__) . '/Fixtures/pre-defined-routes.json'), true);
    }
}
