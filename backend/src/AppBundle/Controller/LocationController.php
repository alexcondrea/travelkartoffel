<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Trivago\Tas\Request\LocationsRequest;
use Trivago\Tas\Response\Locations\Location;

class LocationController extends Controller
{
    /**
     * @Route("/kartoffel/api/search/location", name="trivago_kartoffel_location_search")
     */
    public function indexAction(Request $request)
    {
        $searchTerm = $request->get('q', 'Berlin');

        try {
            $locations = $this->get('trivago.api_workaround')
                ->getLocations(new LocationsRequest($searchTerm));

        } catch (\Trivago\Tas\Response\ProblemException $e) {
            return $this->createNotFoundException('API error: ' . $e->getMessage());
        }

        if($request->query->getBoolean('only_city', true)) {
            $locations = array_values((array_filter($locations->toArray(), function (Location $location) {
                return $location->getType() == 'path';
            })));
        }

        $locs = [];
        foreach($locations as $location) {
            // @TODO: decorate data
            $loc = json_decode($this->get('serializer')->serialize($location, 'json'), true);

            // trivago highlight suggest search "{Dort}mund"
            $loc['nameFormatted'] = str_replace('{', '', $location->getName());
            $loc['nameFormatted'] = str_replace('}', '', $loc['nameFormatted']);

            $locs[] = $loc;
        }

        return $this->json(['items' => $locs]);
    }
}
