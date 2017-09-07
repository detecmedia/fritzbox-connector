<?php
/**
 * Created by PhpStorm.
 * User: markus
 * Date: 06.09.17
 * Time: 09:50
 */

namespace Detecmedia\FritzboxConnector\Model;

/**
 * Class ClientDetails
 * @package Detecmedia\FritzboxConnector\Model
 * @method string getMac() get mac adress;
 * @method string getIpv4() get ip v4
 * @method string getIpv6() get ip v6
 * @method string getType() get type
 * @method array getProperties
 * @method array getOptions
 */
class ClientDetails extends Client
{
    public function __call($methodName, $params = null)
    {
        $methodPrefix = substr($methodName, 0, 3);
        $key = strtolower(substr($methodName, 3));

        $paramsCount = count($params);

        if($methodPrefix === 'set' && count($params) == 2)
        {
            $key = $params[0];
            $value = $params[1];
            $this->$key = $value;
        }
        else if($methodPrefix === 'get' && count($params) == 0)
        {
            if(property_exists($this,$key)) return $this->$key;
        }
    }


    /**
     * ClientDetails constructor.
     */
    public function __construct(array $clientData)
    {
        foreach($clientData as $key => $value){
            $new = strtolower($key);
            $this->{$new} = $value;
        }
    }

    /**
     * @param bool $active
     * @return ClientDetails
     */
    public function setActive(bool $active): ClientDetails
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }
}