<?php

namespace AppBundle\Controller;

use AppBundle\Utils\ParameterUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Trivago\Tas\Request\HotelCollectionRequest;
use Trivago\Tas\Response\HotelCollection\Hotel;
use Trivago\Tas\Response\ProblemException;
use Trivago\Tas\Tas;

class SearchController extends Controller
{
    /**
     * @Route("/kartoffel/api/search/hotel-collection", name="trivago_kartoffel_search_hotel")
     */
    public function hotelProxyAction(Request $request)
    {
        $keys = ParameterUtils::normalizer($request->query->all());

        // resolve path name to id
        if(isset($keys['path']) && !is_numeric($keys['path'])) {
            if(null === $location = $this->get('trivago.location_resolver')->resolveLocationString($keys['path'])) {
                return $this->createNotFoundException('Location not found', $request->query->get('path'));
            }

            $keys['path'] = $location->getPathId();
        }

        $request = new HotelCollectionRequest($keys);


        try {
            $hotels = $this->get('trivago.api_workaround')
                ->getHotelCollection($request);
        } catch (ProblemException $e) {
            return $this->createNotFoundException('API error: ' . $e->getMessage());
        }

        $hotels = $hotels->toArray();

        uasort($hotels, function (Hotel $a, Hotel $b) {
            if ($a->getBestDeal()->getPrice() == $b->getBestDeal()->getPrice()) {
                return 0;
            }

            return ($a->getBestDeal()->getPrice() < $b->getBestDeal()->getPrice()) ? -1 : 1;
        });

        $locs = [];
        foreach($hotels as $hotel) {
            // @TODO: decorate data
            $locs[] = json_decode($this->get('serializer')->serialize($hotel, 'json'), true);
        }

        return $this->json(['items' => $locs]);
    }
}
