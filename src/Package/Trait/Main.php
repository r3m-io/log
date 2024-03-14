<?php
namespace Package\R3m\Io\Log\Trait;

use R3m\Io\Config;

use R3m\Io\Module\Core;
use R3m\Io\Module\File;
use R3m\Io\Module\Dir;

use Exception;

trait Main {

    public function log_clear($flags, $options){
        if(!property_exists($options, 'channel')){
            throw new Exception('Option channel is required...');
        }
        $object = $this->object();
        $log = $object->config('log');
        ddd($log);
        d($object->config('project.dir.log'));
        d($flags);
        d($options);
    }
}