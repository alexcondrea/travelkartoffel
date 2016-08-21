<?php

namespace AppBundle\Calculator;

use AppBundle\Model\HotelsSteps;
use AppBundle\Model\StepCalculation;
use AppBundle\Utils\ArrayUtil;

class StepCalculator
{
    /**
     * @var PriceCalculator
     */
    private $priceCalculator;

    /**
     * StepCalculaor constructor.
     *
     * @param PriceCalculator $priceCalculator
     */
    public function __construct(\AppBundle\Calculator\PriceCalculator $priceCalculator)
    {
        $this->priceCalculator = $priceCalculator;
    }

    /**
     * @param \DateTime $startDate
     * @param HotelsSteps $hotelsSteps
     * @return StepCalculation
     */
    public function getPriceCalculation(\DateTime $startDate, HotelsSteps $hotelsSteps)
    {
        $r = [];

        foreach (ArrayUtil::getArrayPermutations($hotelsSteps->getLocations()) as $steps) {
            $stepStart = clone $startDate;

            $result = ['steps' => []];
            foreach ($steps as $step) {
                $locationStep = $hotelsSteps->findByLocation($step);

                $price = $this->priceCalculator
                    ->getHotelPriceForRange($locationStep->getLocation(), $stepStart, $locationStep->getNights());

                $result['steps'][] = [
                    'location' => $locationStep->getLocation(),
                    'nights' => $locationStep->getNights(),
                    'price' => $price,
                    'start' => $stepStart->format('Y-m-d'),
                    'end' => (clone $stepStart)->modify('+ ' . $locationStep->getNights() . ' days')->format('Y-m-d'),
                ];


                $stepStart->modify('+ ' . $locationStep->getNights() . ' days');
            }

            $result['price'] = array_sum(array_column($result['steps'], 'price'));

            $r[] = $result;
        };

        return new StepCalculation($r);
    }
}