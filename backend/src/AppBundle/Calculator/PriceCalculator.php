<?php

namespace AppBundle\Calculator;

use AppBundle\Trivago\ApiWorkaround;
use Trivago\Tas\Request\HotelCollectionRequest;
use Trivago\Tas\Request\LocationsRequest;
use Trivago\Tas\Response\ProblemException;

class PriceCalculator
{
    /**
     * @var ApiWorkaround
     */
    private $apiWorkaround;

    /**
     * DistanceCalculator constructor.
     *
     * @param ApiWorkaround $apiWorkaround
     */
    public function __construct(ApiWorkaround $apiWorkaround)
    {
        $this->apiWorkaround = $apiWorkaround;
    }

    /**
     * @param string $city
     * @param \DateTime $start
     * @param int $nights
     *
     * @return null|\Trivago\Tas\Response\Common\Price
     */
    function getHotelPriceForRange($city, \DateTime $start, $nights)
    {
        $location = $this->apiWorkaround->getLocations(new LocationsRequest($city))->current();

        $request = new HotelCollectionRequest([
            HotelCollectionRequest::PATH => $location->getPathId(),
            HotelCollectionRequest::START_DATE => $start,
            HotelCollectionRequest::CURRENCY => 'EUR',
            HotelCollectionRequest::END_DATE => (clone $start)->modify('+' . $nights . ' days'),
        ]);

        try {
            $hotels = $this->apiWorkaround->getHotelCollection($request);
            return trim($hotels->current()->getBestDeal()->getPrice(), '€') * $nights;
        } catch (ProblemException $e) {
            return null;
        }
    }
}