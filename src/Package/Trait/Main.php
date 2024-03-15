<?php
namespace Package\R3m\Io\Log\Trait;

use R3m\Io\Config;

use R3m\Io\Module\Data;
use R3m\Io\Module\File;

use Exception;

trait Main {

    public function log_clear($flags, $options){
        if(!property_exists($options, 'channel')){
            throw new Exception('Option channel is required...');
        }
        $object = $this->object();
        if(!is_array($options->channel)){
            $options->channel = [$options->channel];
        }
        foreach($options->channel as $channel){
            $log = $object->config('log.' . $channel);
            if($log){
                $log = new Data($log);
                $handler = $log->get('handler');
                if(is_array($handler)){
                    foreach($handler as $node){
                        $node = new Data($node);
                        ddd($node);
                        $parameters = $node->get('parameters');
                        if($parameters){
                            $parameters = new Data($parameters);
                            $parameters = Config::parameters($object, $parameters);
                            ddd($parameters);
                        }
                    }
                }
            }
        }

        d($flags);
        d($options);
    }
}