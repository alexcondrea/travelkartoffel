<?php

namespace AppBundle\Model;

class Step
{
    /**
     * @var string
     */
    private $location;

    /**
     * @var string
     */
    private $nights;

    /**
     * Steps constructor.
     *
     * @param string $location
     * @param string $nights
     */
    public function __construct($location, $nights)
    {
        $this->location = $location;
        $this->nights = $nights;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return string
     */
    public function getNights()
    {
        return $this->nights;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return md5($this->nights . $this->location);
    }
}