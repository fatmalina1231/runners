<?php

namespace App\Serializer;

use App\Entity\Calendar;
use App\Entity\Event;
use Doctrine\Common\Collections\Collection;

/**
 * Description of JsonApiAdapter
 *
 * @author bepetitcollot
 */
class JsonApiAdapter
{

    private $types;

    public function __construct()
    {
        $this->types = array(
            Calendar::class => array(
                'type' => 'calendar',
                'attributes' => array('name'),
                'relationships' => array('events'),
            ),
            Event::class => array(
                'type' => 'event',
                'attributes' => array('title', 'content', 'date'),
                'relationships' => array('calendar'),
            ),
        );
    }

    public function render($data, $deep = 0)
    {
        if ($data instanceof Collection) $data = $data->toArray();
        $included = array();
        $includedData = array();
        $result = array('data' => $this->dataToArray($data, $included));
        $encoded = is_array($data) ? $data : array($data);
        for ($i = $deep - 1; $i >= 0; $i--) {
            $toEncode = $included;
            $included = array();
            foreach ($toEncode as $objectToEncode) {
                if (!in_array($objectToEncode, $encoded)) {
                    $includedData[] = $this->dataToArray($objectToEncode, $included);
                    $encoded[] = $objectToEncode;
                }
            }
        }
        if (count($includedData) > 0)
            $result['included'] = $includedData;

        return $result;
    }

    private function dataToArray($object, &$included, $onlyReference = false)
    {
        if (is_array($object) || $object instanceof Collection)
            return $this->collectionToArray($object, $included, $onlyReference);
        else
            return $this->objectToArray($object, $included, $onlyReference);
    }

    private function collectionToArray($collection, &$included, $onlyReference = false)
    {
        return array_map(function($object) use (&$included, $onlyReference) {
            if (is_array($included) && !in_array($object, $included))
                $included[] = $object;
            return $this->dataToArray($object, $included, $onlyReference);
        }, $collection instanceof Collection ? $collection->toArray() : $collection);
    }

    private function objectToArray($object, &$included, $onlyReference = false)
    {
        $class = array_reduce(array_keys($this->types), function($carry, $type) use ($object){
            return $object instanceof $type ? $type : $carry;
        }, null);
        $result = array(
            'type' => $this->types[$class]['type'],
            'id' => $object->getId(),
        );
        if (!$onlyReference) {
            foreach ($this->types[$class]['attributes'] as $attrName => $attr) {
                $result['attributes'][is_int($attrName) ? $attr : $attrName] = $object->{'get' . ucfirst($attr)}();
            }
            foreach ($this->types[$class]['relationships'] as $relName => $rel) {
                $result['relationships'][is_int($relName) ? $rel : $relName] = array('data' => $this->dataToArray($object->{'get' . ucfirst($rel)}(), $included, true));
            }
        }

        return $result;
    }

}
