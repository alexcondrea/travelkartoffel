<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Trivago\Tas\Request\HotelCollectionRequest;
use Trivago\Tas\Response\ProblemException;
use Trivago\Tas\Tas;

class SuggestController extends Controller
{
    const PARAMETERS = [
        'path', 'item', 'start_date', 'end_date',
        'room_type', 'currency', 'category', 'limit',
        'offset', 'order', 'rating_class', 'hotel_name', 'max_price'
    ];

    /**
     * @Route("/kartoffel/api/search/hotel-collection", name="trivago_kartoffel_search_hotel")
     */
    public function hotelProxyAction(Request $request)
    {
        $keys = array_merge(['currency' => 'EUR'],
            array_intersect_key($request->query->all(), array_fill_keys(static::PARAMETERS, 1))
        );

        // resolve path name to id
        if(isset($keys['path']) && !is_numeric($keys['path'])) {
            if(null === $location = $this->get('trivago.location_resolver')->resolveLocationString($keys['path'])) {
                return $this->createNotFoundException('Location not found', $request->query->get('path'));
            }

            $keys['path'] = $location->getPathId();
        }

        $request = new HotelCollectionRequest($keys);

        try {
            $locations = $this->get('trivago.tas.client')
                ->getHotelCollection($request);
        } catch (ProblemException $e) {
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
