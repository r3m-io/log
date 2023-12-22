<?php
namespace Package\R3m\Io\Server\Trait;

use R3m\Io\App;

use R3m\Io\Module\Core;
use R3m\Io\Module\File;

use R3m\Io\Node\Model\Node;

use Exception;
trait Import {

    public function role_system(): void
    {
        $object = $this->object();
        $node = new Node($object);
        $node->role_system_create('r3m_io/log');
    }

    public function log_handler(): void
    {
        $object = $this->object();
        $options = App::options($object);
        ddd($object->request());
        $class = 'System.Log.Handler';
        $options->url = $object->config('project.dir.vendor') .
            'r3m_io/log/Data/' .
            $class .
            $object->config('extension.json')
        ;
        $options->uuid = true;
        $node = new Node($object);
        $response = $node->import($class, $node->role_system(), $options);
        $node->stats($class, $response);
    }
}