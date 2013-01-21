<?php

class plPhuml 
{
    private $properties;
    
    private $files;
    private $processors;
    
    /**
      * The file extensions that the PHP processor should consider when looping through files
      */
    
    public static $DEFAULT_EXTENSIONS = array( 'php', 'phps', 'phtml', 'inc', 'php5' );
    
    public function __construct() 
    {
        $this->properties = array( 
            'generator'     => plStructureGenerator::factory( 'tokenparser' ),
        );

        $this->files = array();
    }

    public function addFile( $file ) 
    {
        $this->files[] = $file;
    }

    function _generateFilenameRegexp( $extensions ){
        if( !is_array( $extensions ) ){
            $extensions = array( $extensions );
        }
        
        $extensions    =    implode(
                            '|',
                            array_map( function( $s ){ return preg_quote( $s, '%' ); }, $extensions )
                        );
        
        return "%.+\.({$extensions})$%";
    }
    
    public function addDirectory( $directory, $extensions = null, $recursive = true ) 
    {
        if( empty( $extensions ) ){
            $extensions = self::$DEFAULT_EXTENSIONS;
        }
        
        $regexp = $this->_generateFilenameRegexp( $extensions );
        
        if ( $recursive === false ) 
        {
            $iterator = new DirectoryIterator( $directory );
        }
        else
        {
            $iterator = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $directory ) );
        }
        
        foreach( $iterator as $entry ) 
        {
            if ( $entry->isDir() === true ) 
            {
                continue;
            }
            
            $filename = basename( $entry->getFilename() );
            
            if( !preg_match( $regexp, $filename ) ){
                continue;
            }
            
            $this->addFile( $entry->getPathname() );
        }
    }
    
    public function addProcessor( $processor ) 
    {
        if ( count( $this->processors ) === 0 ) 
        {
            // First processor must support application/phuml-structure
            if ( !in_array( 'application/phuml-structure', $processor->getInputTypes() ) ) 
            {
                throw new plPhumlInvalidProcessorChainException( 'application/phuml-structure', $processor->getInputTypes() );
            }
        }
        else
        {
            $this->checkProcessorCompatibility( end( $this->processors ), $processor );

        }
        $this->processors[] = $processor;
    }

    private function checkProcessorCompatibility( $first, $second ) 
    {
        if ( !( $first instanceof plProcessor ) || !( $second instanceof plProcessor ) ) 
        {
            throw new plPhumlInvalidProcessorException();
        }

        if ( !in_array( $first->getOutputType(), $second->getInputTypes() ) ) 
        {
            throw new plPhumlInvalidProcessorChainException( $first->getOutputType(), $second->getInputTypes() );
        }
    }

    public function generate( $outfile ) 
    {
        echo "[|] Parsing class structure", "\n";
        $structure = $this->generator->createStructure( $this->files );
        
        $temporary = array( $structure, 'application/phuml-structure' );
        foreach( $this->processors as $processor ) 
        {            
            preg_match( 
                '@^pl([A-Z][a-z]*)Processor$@',
                get_class( $processor ),
                $matches
            );

            echo "[|] Running '" . $matches[1] . "' processor", "\n";
            $temporary = array( 
                $processor->process( $temporary[0], $temporary[1] ),
                $processor->getOutputType(),
            );
        }

        echo "[|] Writing generated data to disk", "\n";
        end( $this->processors )->writeToDisk( $temporary[0], $outfile );
    }


    public function __get( $key )
    {
        if ( !array_key_exists( $key, $this->properties ) )
        {
            throw new plBasePropertyException( $key, plBasePropertyException::READ );
        }
        return $this->properties[$key];
    }

    public function __set( $key, $val )
    {
        if ( !array_key_exists( $key, $this->properties ) )
        {
            throw new plBasePropertyException( $key, plBasePropertyException::WRITE );
        }
        $this->properties[$key] = $val;            
    }

}

?>
