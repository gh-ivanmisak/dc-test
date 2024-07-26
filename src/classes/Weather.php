<?php

class Weather
{
    /** 
     * @var string
     * https://openweathermap.org/forecast5 
    */
    private $API_URL = 'https://api.openweathermap.org/data/2.5/forecast?lat=%f&lon=%f&appid=%s';

    /** @var string */
    private $API_KEY = 'fa71460b38c7460e4e2223a3b75bc738';

    public function validate( $lat , $lan ): bool
    {
        // @todo

        return true;
    }

    /**
     * Returns final url for API call 
     * 
     * @param float $lat - latitude
     * @param float $lon - lognitude
     * 
     * @throws UnexpectedValueException
     * @return string
     */
    private function buildUrl( float $lat , float $lon ): string
    {
        if( -90.0 > $lat || 90.0 < $lat )
        {
            throw new UnexpectedValueException('Latitude must be between -90° and 90°');
        }

        if( -180.0 > $lon || 180.0 < $lon )
        {
            throw new UnexpectedValueException('Lognitude must be between -180° and 180°');
        }

        return printf( $this->API_URL, $lat, $lon, $this->API_KEY );
    }

}