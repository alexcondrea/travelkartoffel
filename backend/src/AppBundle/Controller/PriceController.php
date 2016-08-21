<?php

namespace AppBundle\Controller;

use AppBundle\Model\HotelsSteps;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PriceController extends Controller
{
    /**
     * @Route("/kartoffel/api/price", methods={"GET"})
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
     * @Route("/kartoffel/api/price", methods={"POST"})
     */
    public function ajaxAction(Request $request)
    {
        $date = $request->query->get('start_date', (new \DateTime())->format('Y-m-d'));
        $price = $request->query->get('current_price', 1000);

        $startDate = date_create_from_format('Y-m-d', $date);

        $content = json_decode($request->getContent(), true);

        $prices = $this->get('trivago.step_calculator')->getPriceCalculation($startDate, HotelsSteps::fromArray($content));

        $bestStep = $prices->getBestStep();

        if($bestStep['price'] >= $price) {
            return $this->render('price/ajax-success.html.twig');
        }

        return $this->render('price/ajax.html.twig', [
            'result' => $bestStep,
            'steps' => array_column($bestStep['steps'], 'location'),
            'price_save_up' => $price - $bestStep['price'],
        ]);
    }

    /**
     * @return array
     */
    private function getStorage()
    {
        return json_decode(file_get_contents(dirname(__DIR__) . '/Fixtures/pre-defined-routes.json'), true);
    }
}
