<?php

use R3m\Io\App;
use R3m\Io\Module\Data;
use R3m\Io\Module\Database;
use R3m\Io\Module\Dir;
use R3m\Io\Module\Core;
use R3m\Io\Module\Event;
use R3m\Io\Module\File;
use R3m\Io\Module\Parse;

/**
 * @throws \R3m\Io\Exception\FileWriteException
 * @throws \R3m\Io\Exception\ObjectException
 * @throws \Doctrine\ORM\Exception\ORMException
 * @throws \Doctrine\ORM\ORMException
 * @throws \Doctrine\DBAL\Exception
 */
function function_log_archive(Parse $parse, Data $data){
    $object = $parse->object();
    $source = $object->parameter($object, 'archive', 1);
    $explode = explode('.', File::basename($source));
    if(array_key_exists(1, $explode)){
        $dir = $object->config('project.dir.log') .
            'Archive' .
            $object->config('ds')
        ;
        $destination = $dir .
            $explode[0] .
            '."{date(\'Ymd\')}".' .
            $explode[1] .
            '.zip'
        ;
        if(array_key_exists('_', $_SERVER)){
            $dirname = Dir::name($_SERVER['_']);
            $binary = str_replace($dirname, '', $_SERVER['_']);
            $execute = $binary . ' zip archive ' . $source . ' ' . $destination;
            Core::execute($object, $execute, $output);
            File::chown($dir, 'www-data', 'www-data', true);
            if($object->config('project.log.name')){
                $object->logger($object->config('project.log.name'))->info('log_archive dir', [ $dir ]);
            }
            File::write($source,'');
            $dir = Dir::name($source);
            File::chown($dir, 'www-data', 'www-data', true);
            if($object->config('project.log.name')){
                $object->logger($object->config('project.log.name'))->info('log_archive dir', [ $dir ]);
            }
            Event::trigger($object, 'cli.log.archive', [
                'channel' => $explode[0],
                'source' => $source,
                'destination' => $destination,
                'output' => $output,
            ]);
            echo 'Log file has been reset...' . PHP_EOL;
        }
    }
}
