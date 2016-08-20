<?php

namespace AppBundle\Trivago;

use Trivago\Tas\Request\HotelCollectionRequest;
use Trivago\Tas\Request\LocationsRequest;
use Trivago\Tas\Response\HotelCollection\HotelCollection;
use Trivago\Tas\Response\Locations\Locations;
use Trivago\Tas\Tas;

/**
 * API provides empty array results, after given sleep time;
 * api boot reauth issue?
 *
 * Try to search multiple requests on empty result
 */
class ApiWorkaround
{
    /**
     * @var Tas
     */
    private $tas;

    /**
     * ApiWorkaround constructor.
     *
     * @param Tas $tas
     */
    public function __construct(Tas $tas)
    {
        $this->tas = $tas;
    }

    /**
     * @param HotelCollectionRequest $request
     *
     * @return array|\Trivago\Tas\Response\HotelCollection\HotelCollection
     */
    public function getHotelCollection(HotelCollectionRequest $request) {
        for ($i = 1; $i <= 4; $i++) {
            $result = $this->tas->getHotelCollection($request);
            if(count($result) != 0) {
                return $result;
            }
        }

        return new HotelCollection();
    }

    public function getLocations(LocationsRequest $request)
    {
        for ($i = 1; $i <= 4; $i++) {
            $result = $this->tas->getLocations($request);
            if (count($result) != 0) {
                return $result;
            }
        }

        return new Locations();
    }
}