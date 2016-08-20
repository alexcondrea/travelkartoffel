<?php

namespace AppBundle\Utils;

class ParameterUtils
{
    /**
     * @param array $parameter
     * @return array
     */
    public static function normalizer(array $parameter)
    {
        $parameters = [
            'path',
            'item',
            'start_date',
            'end_date',
            'room_type',
            'currency',
            'category',
            'limit',
            'offset',
            'order',
            'rating_class',
            'hotel_name',
            'max_price'
        ];

        $keys = array_merge(['currency' => 'EUR'],
            array_intersect_key($parameter, array_fill_keys($parameters, 1))
        );

        // resolve path name to id
        if (isset($keys['start_date'])) {
            $keys['start_date'] = date_create_from_format('Y-m-d', $keys['start_date']);
        }

        if (isset($keys['end_date'])) {
            $keys['end_date'] = date_create_from_format('Y-m-d', $keys['end_date']);
        }

        return $keys;
    }
}