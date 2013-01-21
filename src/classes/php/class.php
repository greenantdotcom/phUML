<?php

require_once __DIR__.'/plPhpPropertyClass.php';

class plPhpClass extends plPhpPropertyClass
{
    public function __construct( $name, $attributes = array(), $functions = array(), $implements = array(), $extends = null, $namespace = null ) 
    {
        $this->properties = array( 
            'name'          =>  $name,
            'attributes'    =>  $attributes,
            'functions'     =>  $functions,
            'implements'    =>  $implements,
            'extends'       =>  $extends,
            'namespace'     =>  $namespace,
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
