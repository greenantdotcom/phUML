<?php

require_once __DIR__.'/plPhpPropertyClass.php';

class plPhpFunction extends plPhpPropertyClass
{
    public function __construct( $name, $modifier = 'public', $params = array(), $namespace = null ) 
    {
        $this->properties = array( 
            'name'      =>  $name,
            'modifier'  =>  $modifier,
            'params'    =>  $params,
            'namespace' =>  $namespace,
        );
    }
}