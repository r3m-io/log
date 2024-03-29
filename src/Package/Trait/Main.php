<?php
namespace Package\R3m\Io\Log\Trait;

use R3m\Io\Config;

use R3m\Io\Module\Data;
use R3m\Io\Module\File;

use Exception;

use R3m\Io\Exception\FileWriteException;

trait Main {

    /**
     * @throws FileWriteException
     * @throws Exception
     */
    public function log_clear($flags, $options): void
    {
        if(!property_exists($options, 'channel')){
            throw new Exception('Option channel is required...');
        }
        $object = $this->object();
        if(!is_array($options->channel)){
            $options->channel = [$options->channel];
        }
        foreach($options->channel as $channel){
            if(
                in_array(
                    $channel,
                    [
                        'apache_access',
                        'apache2_access',
                        'apache_error',
                        'apache2_error',
                    ], true
                )
            ){
                $url = $object->config('project.dir.log') . '/' . $channel . '.log';
                File::write($url, '');
            } else {
                $log = $object->config('log.' . $channel);
                if($log){
                    $log = new Data($log);
                    $handler = $log->get('handler');
                    if(is_array($handler)){
                        foreach($handler as $node){
                            $node = new Data($node);
                            $parameters = $node->get('options.parameters');
                            if($parameters){
                                $parameters = Config::parameters($object, $parameters);
                                if(
                                    array_key_exists(0, $parameters) &&
                                    File::exist($parameters[0])
                                ){
                                    File::write($parameters[0], '');
                                }
                            }
                        }
                    }
                }
            }

        }
    }
}