<?php

namespace AppBundle\Trivago;

use Psr\Cache\CacheItemPoolInterface;
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
    const CACHE_LIFETIME = 3600;

    /**
     * @var Tas
     */
    private $tas;
    /**
     * @var CacheItemPoolInterface
     */
    private $cacheItemPool;

    /**
     * ApiWorkaround constructor.
     *
     * @param Tas $tas
     */
    public function __construct(Tas $tas, CacheItemPoolInterface $cacheItemPool)
    {
        $this->tas = $tas;
        $this->cacheItemPool = $cacheItemPool;
    }

    /**
     * @param HotelCollectionRequest $request
     *
     * @return array|\Trivago\Tas\Response\HotelCollection\HotelCollection
     */
    public function getHotelCollection(HotelCollectionRequest $request) {
        $key = md5($request->getPath() . json_encode($request->getQueryParameters()));
        $cache = $this->cacheItemPool->getItem($key);
        if ($cache->isHit()) {
            return $cache->get();
        }

        for ($i = 1; $i <= 4; $i++) {
            $result = $this->tas->getHotelCollection($request);
            if(count($result) != 0) {
                $this->cacheItemPool->save(
                    $cache->set($result)->expiresAfter(self::CACHE_LIFETIME)
                );

                return $result;
            }
        }

        return new HotelCollection();
    }

    public function getLocations(LocationsRequest $request)
    {
        $key = md5($request->getPath() .  json_encode($request->getQueryParameters()));
        $cache = $this->cacheItemPool->getItem($key);
        if ($cache->isHit()) {
            return $cache->get();
        }

        for ($i = 1; $i <= 4; $i++) {
            $result = $this->tas->getLocations($request);
            if (count($result) != 0) {
                $this->cacheItemPool->save(
                    $cache->set($result)->expiresAfter(self::CACHE_LIFETIME)
                );
                return $result;
            }
        }

        return new Locations();
    }
}