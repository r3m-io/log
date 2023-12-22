<?php
namespace Package\R3m\Io\Log\Trait;

use R3m\Io\App;

use R3m\Io\Module\Core;
use R3m\Io\Module\File;

use R3m\Io\Node\Model\Node;

use Exception;
trait Import {

    public function role_system(): void
    {
        $object = $this->object();
        $package = $object->request('package');
        if($package){
            $node = new Node($object);
            $node->role_system_create($package);
        }
    }

    public function log_handler(): void
    {
        $object = $this->object();
        $package = $object->request('package');
        if($package){
            $options = App::options($object);
            $class = 'System.Log.Handler';
            $options->url = $object->config('project.dir.vendor') .
                $package . '/Data/' .
                $class .
                $object->config('extension.json')
            ;
            $options->uuid = true;
            $node = new Node($object);
            $response = $node->import($class, $node->role_system(), $options);
            $node->stats($class, $response);
        }

    }
}