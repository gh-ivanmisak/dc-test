<?php

class Weather
{
    /** 
     * @var string
     * https://openweathermap.org/forecast5 
    */
    private $API_URL_FORECAST = 'https://api.openweathermap.org/data/2.5/forecast?lat=%f&lon=%f&appid=%s';

    /**
     * @var string
     * https://openweathermap.org/api/geocoding-api
     */
    private $API_URL_CITY = 'http://api.openweathermap.org/geo/1.0/direct?q=%s&limit=5&appid=%s';

    /** 
     * @var string 
     * 
     * @todo Move into config file
     */
    private $API_KEY = 'fa71460b38c7460e4e2223a3b75bc738';

    /** @var float */
    private $lat = 0.00;
    
    /** @var float */
    private $lon = 0.00;

    /** @var string */
    private $city;

    /* constants */
    const TYPE_FORECAST = 'forecast';
    const TYPE_CITY = 'city';

    /** 
     * Setter for GPS coordinates = latitude and lognitude 
     * 
     * @param mixed $lat - latitude
     * @param mixed $lon - lognitude
     * 
     * @return void
     */
    public function set( $lat, $lon ): void
    {
        // formatting into float
        if( 'double' != gettype( $lat ) )
        {
            $lat = floatval( $lat );
        }

        if( 'double' != gettype( $lon ) )
        {
            $lon = floatval( $lon );
        }

        // setting data
        $this->lat = $lat;
        $this->lon = $lon;
    }

    /**
     * Getter for latitude
     * 
     * @return float
     */
    public function getLat(): float
    {
        return $this->lat;
    }

    /**
     * Getter for lognitude
     * 
     * @return float
     */
    public function getLog(): float
    {
        return $this->log;
    }

    /**
     * Setter for city name
     * 
     * @param string $city
     * 
     * @return void
     */
    public function setCity( string $city ): void
    {
        $this->city = $city;
    }

    /**
     * Getter for city name
     * 
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * Get geocoordinates for city
     * 
     * @throws UnexpectedValueException
     * @return bool
     */
    public function getCityGeocoordinates(): bool
    {
        if( null == $this->city )
        {
            throw new UnexpectedValueException('No city has been specified');
        }

        $data = $this->call( self::TYPE_CITY );

        // catch no data option
        if( 0 == count( $data ) )
        {
            return false;
        }

        // set returned geocoordinates
        // i am using first result because I assume that it is the most relevant result
        $this->set( $data[0]['lat'], $data[0]['lon'] ); 
        
        return true;
    }

    /**
     * Validation of current GPS coordinates
     * 
     * @param bool $throwable - represents type of return on fail (true throws error directly)
     * 
     * @throws UnexpectedValueException
     * @return bool
     */
    public function validate( bool $throwable = false ): bool
    {
        if( -90.0 > $this->lat || 90.0 < $this->lat )
        {
            if( false == $throwable )
            {
                return false;
            }
            throw new UnexpectedValueException('Latitude must be between -90° and 90°');
        }

        if( -180.0 > $this->lon || 180.0 < $this->lon )
        {
            if( false == $throwable )
            {
                return false;
            }
            throw new UnexpectedValueException('Lognitude must be between -180° and 180°');
        }

        return true;
    }

    /**
     * Returns final url for API call - forecast
     * 
     * @return string
     */
    private function buildUrl_Forecast(): string
    {
        return sprintf( $this->API_URL_FORECAST, $this->lat, $this->lon, $this->API_KEY );
    }

    /**
     * Returns final url for API call - city geocoordinates
     * 
     * @return string
     */
    private function buildUrl_City(): string
    {
        return sprintf( $this->API_URL_CITY, $this->city , $this->API_KEY );
    }


    /**
     * Calls cUrl API call for given type of call
     * 
     * @param string $type - type of API endpoind, allowed values are 'forecast' and 'city'
     * 
     * @throws Exception
     * @return stdClass|array
     */
    public function call( string $type = self::TYPE_FORECAST )
    {
        $ch = curl_init();
        
        // getting correct endpoint url address for cUrl call
        if( false == in_array( $type , [self::TYPE_CITY, self::TYPE_FORECAST] ) )
        {
            throw new Exception('Api call error: Wrong type of call. Allowed are [' . join(', ', [ self::TYPE_CITY, self::TYPE_FORECAST ] ) . ']' );
        }

        switch($type)
        {
            case self::TYPE_CITY:
                $endpointUrl = $this->buildUrl_City();
                break;

            case self::TYPE_FORECAST:
            default:
                $endpointUrl = $this->buildUrl_Forecast();
                break;
        }

        // settings
        curl_setopt($ch, CURLOPT_URL, $endpointUrl );
        curl_setopt($ch, CURLOPT_HTTPHEADER, [ 'Accept: application/json', 'Content-Type: application/json' ] );
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        // calling endpoint
        $response = curl_exec($ch);

        // processing curl errors
        if ( curl_errno($ch) ) 
        {
            throw new Exception( 'cUrl error: ' . curl_error($ch) );
        } 

        // converting data to readable format
        $response = json_decode( $response );

        // processing api errors
        if( 'array' != gettype($response) )
        {
            if( 200 != (int) $response->cod )
            {
                throw new Exception( 'API error: ' . $response->message );
            }
        }
        else
        {
            if( 0 == sizeof( $response ) )
            {
                throw new Exception( 'API error: no results has been found' );
            }
        }

        // end connection
        curl_close($ch);

        return $response;
    }

}