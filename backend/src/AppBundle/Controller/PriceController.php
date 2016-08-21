<?php

namespace AppBundle\Controller;

use AppBundle\Model\HotelsSteps;
use AppBundle\Utils\ArrayUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PriceController extends Controller
{
    /**
     * @Route("/kartoffel/api/price")
     */
    public function indexAction(Request $request)
    {
        $date = $request->query->get('start_date', (new \DateTime())->format('Y-m-d'));
        $startDate = date_create_from_format('Y-m-d', $date);

        $prices = $this->get('trivago.step_calculator')->getPriceCalculation($startDate, HotelsSteps::fromArray(
            current($this->getStorage())['steps']
        ));

        return $this->json(json_decode($this->get('serializer')->serialize($prices, 'json'), true));
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
