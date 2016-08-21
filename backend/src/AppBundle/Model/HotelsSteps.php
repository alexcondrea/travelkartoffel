<?php

namespace AppBundle\Model;

class HotelsSteps
{
    /**
     * @var array
     */
    private $steps;

    /**
     * HotelsSteps constructor.
     *
     * @param Step[] $steps
     */
    public function __construct(array $steps)
    {
        $this->steps = $steps;
    }

    /**
     * @return Step[]
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * @param string $location
     *
     * @return Step
     */
    public function findByLocation($location)
    {
        foreach ($this->steps as $step) {
            if ($step->getLocation() == $location) {
                return $step;
            }
        }

        throw new \OutOfBoundsException('invalid location');
    }

    /**
     * @return array<string>
     */
    public function getLocations()
    {
        return array_map(function(Step $step) {
            return $step->getLocation();
        }, $this->steps);
    }

    /**
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data)
    {
        return new static(array_map(function(array $item) {
            return new Step($item['location'], $item['nights']);
        }, $data));
    }
}