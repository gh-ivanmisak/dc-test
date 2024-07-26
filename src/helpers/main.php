<?php

if( false == function_exists('d') )
{
    function d( $value )
    {
        print '<pre>' . print_r( $value , true ) . '</pre>';
    }
}

if( false == function_exists('dd') )
{
    function dd( $value )
    {
        d( $value );
        die;
    }
}