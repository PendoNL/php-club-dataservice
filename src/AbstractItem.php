<?php

namespace PendoNL\ClubDataservice;

class AbstractItem
{
    public $api;

    /**
     * @param Api $api
     */
    public function __construct(Api $api, $data = [])
    {
        $this->api = $api;
        foreach($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * Get all the public properties
     *
     * @return array
     */
    public function toArray()
    {
        return call_user_func('get_object_vars', $this);
    }
}
