<?php

require_once __DIR__.'/plPhpPropertyClass.php';

class plPhpFunctionParameter extends plPhpPropertyClass
{
    public function __construct( $name, $type = null, $reference = false ) 
    {
        $namePrepend = ( $reference ) ? '&' : null;
        
        $this->properties = array( 
            'name'      =>  $namePrepend.$name,
            'type'      =>  $type,
        );
    }
}

?>
