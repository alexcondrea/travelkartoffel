<?php

namespace AppBundle\Model;

class StepCalculation
{
    /**
     * @var array
     */
    private $result;

    /**
     * StepCalculation constructor.
     *
     * @param array $result
     */
    public function __construct(array $result)
    {
        $this->result = $result;
    }

    /**
     * @return array
     */
    public function getBestStep()
    {
        $cheapest = null;

        foreach($this->result as $result) {
            if($cheapest == null || $result['price'] < $cheapest['price']) {
                $cheapest = $result;
            }
        }

        return $cheapest;
    }

    /**
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }
}