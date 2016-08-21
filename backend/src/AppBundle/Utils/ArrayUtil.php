<?php

namespace AppBundle\Utils;

class ArrayUtil
{
    /**
     * @param array $items
     * @return array
     */
    public static function getArrayPermutations(array $items)
    {
        $result = [];
        static::getArrayPermutationsInner($items, [], $result);
        return $result;
    }

    /**
     * @param $items
     * @param array $perms
     * @param array $result
     */
    public static function getArrayPermutationsInner($items, $perms = [], &$result = [])
    {
        if (empty($items)) {
            $result[] = $perms;
        } else {
            for ($i = count($items) - 1; $i >= 0; --$i) {
                $newitems = $items;
                $newperms = $perms;
                list($foo) = array_splice($newitems, $i, 1);
                array_unshift($newperms, $foo);
                static::getArrayPermutationsInner($newitems, $newperms, $result);
            }
        }
    }
}