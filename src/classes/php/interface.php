<?php

require_once __DIR__.'/plPhpPropertyClass.php';

class plPhpInterface extends plPhpPropertyClass
{
    public function __construct( $name, $functions = array(), $extends = null, $namespace = null ) 
    {
        $this->properties = array( 
            'name'      =>  $name,
            'functions' =>  $functions,
            'extends'   =>  $extends,
            'namespace' =>  $namespace,
        );
    }

    public function formattedName( $value ){
        $ns = $this->namespace;
        
        if( !empty( $ns ) ){
            if( $ns[0] <> '\\' ){
                $ns = '\\'.$ns;
            }
            
            $ns .= '\\';
        }
        
        return $ns.$this->name;
    }
}