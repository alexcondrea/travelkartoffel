<?php

namespace AppBundle\Calculator;

use AppBundle\Model\HotelsSteps;
use AppBundle\Model\StepCalculation;
use AppBundle\Utils\ArrayUtil;
use Psr\Cache\CacheItemPoolInterface;

class StepCalculator
{
    const CACHE_LIFETIME = 3600;
    /**
     * @var PriceCalculator
     */
    private $priceCalculator;

    /**
     * @var CacheItemPoolInterface
     */
    private $cacheItemPool;

    /**
     * StepCalculaor constructor.
     *
     * @param PriceCalculator $priceCalculator
     */
    public function __construct(PriceCalculator $priceCalculator, CacheItemPoolInterface $cacheItemPool)
    {
        $this->priceCalculator = $priceCalculator;
        $this->cacheItemPool = $cacheItemPool;
    }

    /**
     * @param \DateTime $startDate
     * @param HotelsSteps $hotelsSteps
     * @return StepCalculation
     */
    public function getPriceCalculation(\DateTime $startDate, HotelsSteps $hotelsSteps)
    {
        $r = [];

        $key = md5($hotelsSteps->getHash() . $startDate->format('Y-m-d'));
        $cache = $this->cacheItemPool->getItem($key);

        if($cache->isHit()) {
            return new StepCalculation($cache->get());
        }

        foreach (ArrayUtil::getArrayPermutations($hotelsSteps->getLocations()) as $steps) {
            $stepStart = clone $startDate;

            $result = ['steps' => []];
            foreach ($steps as $step) {
                $locationStep = $hotelsSteps->findByLocation($step);

                $price = $this->priceCalculator
                    ->getHotelPriceForRange($locationStep->getLocation(), $stepStart, $locationStep->getNights());

                $dateTime = clone $stepStart;

                $result['steps'][] = [
                    'location' => $locationStep->getLocation(),
                    'nights' => $locationStep->getNights(),
                    'price' => $price,
                    'start' => $stepStart->format('Y-m-d'),
                    'end' => $dateTime->modify('+ ' . $locationStep->getNights() . ' days')->format('Y-m-d'),
                ];


                $stepStart->modify('+ ' . $locationStep->getNights() . ' days');
            }

            $result['price'] = array_sum(array_column($result['steps'], 'price'));

            $r[] = $result;
        };

        $cache->set($r)->expiresAfter(self::CACHE_LIFETIME);
        $this->cacheItemPool->save($cache);

        return new StepCalculation($r);
    }
}