<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Trivago\Tas\Config;
use Trivago\Tas\Request\LocationsRequest;
use Trivago\Tas\Tas;

class HotelController extends Controller
{
    /**
     * @Route("/kartoffel/api/search", name="trivago_kartoffel_search")
     */
    public function indexAction(Request $request)
    {
        $config = $this->get('trivago.tas.config');

        $tas = new Tas($config);

        $searchTerm = $request->get('q', 'Berlin');

        try {
            $locations = $tas->getLocations(new LocationsRequest($searchTerm));
        } catch (\Trivago\Tas\Response\ProblemException $e) {
            return $this->createNotFoundException('API error: ' . $e->getMessage());
        }

        $locs = [];
        foreach($locations as $location) {
            // @TODO: decorate data
            $locs[] = json_decode($this->get('serializer')->serialize($location, 'json'), true);
        }

        return $this->json(['items' => $locs]);
    }
}
