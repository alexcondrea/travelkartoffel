<?php

namespace AppBundle\Resolver;

use Trivago\Tas\Request\LocationsRequest;
use Trivago\Tas\Response\ProblemException;
use Trivago\Tas\Tas;

class LocationResolver
{
    /**
     * @var Tas
     */
    private $tas;

    /**
     * LocationResolver constructor.
     *
     * @param Tas $tas
     */
    public function __construct(Tas $tas)
    {
        $this->tas = $tas;
    }

    /**
     * @param string $search
     * @return null|\Trivago\Tas\Response\Locations\Location
     */
    public function resolveLocationString($search)
    {
        try {
            $locations = $this->tas
                ->getLocations(new LocationsRequest($search));
        } catch (ProblemException $e) {
            return null;
        }

        if($locations->count() == 0) {
            return null;
        }

        return $locations->current();
    }
}
