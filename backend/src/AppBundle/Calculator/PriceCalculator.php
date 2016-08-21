<?php

namespace AppBundle\Calculator;

use AppBundle\Trivago\ApiWorkaround;
use Trivago\Tas\Request\HotelCollectionRequest;
use Trivago\Tas\Request\LocationsRequest;
use Trivago\Tas\Response\HotelCollection\Hotel;
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

        $dateTime = clone $start;

        $request = new HotelCollectionRequest([
            HotelCollectionRequest::PATH => $location->getPathId(),
            HotelCollectionRequest::START_DATE => $start,
            HotelCollectionRequest::CURRENCY => 'EUR',
            HotelCollectionRequest::END_DATE => $dateTime->modify('+' . $nights . ' days'),
        ]);

        try {
            $hotels = $this->apiWorkaround->getHotelCollection($request);
            $hotels = $hotels->toArray();

            uasort($hotels, function (Hotel $a, Hotel $b) {
                if ($this->formatPrice($a->getBestDeal()->getPrice()) == $this->formatPrice($b->getBestDeal()->getPrice())) {
                    return 0;
                }

                return ($this->formatPrice($a->getBestDeal()->getPrice()) < $this->formatPrice($b->getBestDeal()->getPrice())) ? -1 : 1;
            });

            return trim($hotels[0]->getBestDeal()->getPrice(), '€') * $nights;
        } catch (ProblemException $e) {
            return null;
        }
    }

    private function formatPrice($price)
    {
        return intval(trim($price, '€'));
    }
}