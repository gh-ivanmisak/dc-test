<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Weather
{
    /** 
     * @var string
     * https://openweathermap.org/forecast5 
     * 
     * @note This API endpoind shows forecast only for next 5 days
     * @todo UI datepicker should not allow more than range 5 days (fe. 26 - 31.7)
    */
    private $API_URL_FORECAST = 'https://api.openweathermap.org/data/2.5/forecast?lat=%f&lon=%f&appid=%s&units=metric';

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
     * Main function for processing form
     * 
     * @return void
     */
    public function run(): void
    {
        /* ------- PROCESSING EXPORT CALL ------- */
        $this->export();

        /* ------- PROCESSING AJAX CALL ------- */
        if( false == isset( $_POST['city'] ) )
        {
            return;
        }

        // default response
        $response = [
            'code' => 500,
            'msg' => 'There was an error while submiting form'
        ];

        // there all of the magic begins..
        if( 0 < strlen( $_POST['city'] ) )
        {
            try
            {
                // setting up user inserted city into class
                $this->setCity( $_POST['city'] );

                // getting geocoordinates for inserted city
                if( true == $this->getCityGeocoordinates() )
                {
                    // if was filled date too, we take this information
                    // otherwise current date will be used
                    $date = isset( $_POST['date'] ) ? $_POST['date'] : null;

                    // obtaining data
                    $data = $this->formatForecastData( $date );

                    if( count($data) )
                    {
                        // generating html response
                        $html = '';
                        foreach( $data as $row )
                        {
                            ob_start();
                            include _WWW_DIR_ . '/includes/_tpl-row.php';
                            $html .= ob_get_contents();
                            ob_end_clean();
                        }

                        // updating ajax response with obtained data
                        $response['code'] = 200;
                        $response['html'] = $html;
                        unset( $response['msg'] );
                    }
                    // else: data variable is empty
                    else
                    {
                        $response['msg'] = 'No data has been found';
                    }
                }
                // else: there was problem getting geocoordinates
                else
                {
                    $response['msg'] = 'There was problem while getting geocoordinates';
                }

            }
            // according all errors has their own message, we show it
            // in real life is it better only show to user message, that something was wrong and log an error for me
            catch( Exception $e )
            {

                $response['msg'] = $e->getMessage();
            }
            catch( UnexpectedValueException $e )
            {
                $response['msg'] = $e->getMessage();
            }
        }
        else
        {
            $response['msg'] = 'City name is required field';
        }

        // show response and end next processing of source
        echo json_encode( $response );
        exit();
    }

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
        $this->city = trim( $city );
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
     * Get geocoordinates for city
     * 
     * @throws UnexpectedValueException
     * @return bool
     * 
     * @todo Make function private
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
        $this->set( $data[0]->lat, $data[0]->lon ); 
        
        return true;
    }

    /**
     * Get and format forecast data for given date and returns in required format for next steps
     * If date is not specified, function uses today's date
     * 
     * @param string $date - in YYYY-mm-dd format, otherwise throws Exception
     * 
     * @throws Exception
     * @return array
     */
    public function formatForecastData( string $date = null ): array{

        // if no date is given, set today as date
        if( null == $date )
        {
            $date = date('Y-m-d');
        }

        // getting interval points
        $date_from = new DateTime( $date . ' 00:00:00');
        $date_to = new DateTime( $date . ' 23:59:59');
        
        $result = [];

        // get the data
        $data = $this->call();

        // collect the data in usable format
        foreach( $data->list as $row )
        {
            $date = new DateTime('@' . $row->dt );
            if( $date >= $date_from && $date <= $date_to )
            {
                $obj = new stdClass;
                $obj->city = $this->city;
                $obj->date = $date;
                $obj->temp = $row->main->temp;
                $obj->desc = '--';

                if( true == isset( $row->weather[0]->description ) )
                {
                    $obj->desc = $row->weather[0]->description;
                }
                else if( true == isset( $row->weather->description ) )
                {
                    $obj->desc = $row->weather->description;    
                }
                
                
                $result[] = $obj;
            }
        }

        return $result;
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

    /**
     * Handle function for export get and download data
     * 
     * @return void
     */
    public function export(): void
    {
        if( true == isset($_GET['download']) && true == isset($_GET['city']) )
        {
            $date = true == $_GET['date'] ? $_GET['date'] : null;

            $this->download( $_GET['city'], $date );
        }
    }

    public function download( string $city, string $date = null ): void
    {
        $this->setCity( $city );

        // getting geocoordinates for inserted city
        if( true == $this->getCityGeocoordinates() )
        {
            // obtaining data
            $data = $this->formatForecastData( $date );

            if( count($data) )
            {
                $spreadsheet = new Spreadsheet();
                $activeWorksheet = $spreadsheet->getActiveSheet();

                // header
                $rowNo = 1;
                $activeWorksheet->setCellValue('A'.$rowNo, 'City name');
                $activeWorksheet->setCellValue('B'.$rowNo, 'Date');
                $activeWorksheet->setCellValue('C'.$rowNo, 'Temperature');
                $activeWorksheet->setCellValue('D'.$rowNo, 'Description');

                // content
                foreach( $data as $row )
                {
                    $rowNo++;

                    $activeWorksheet->setCellValue('A'.$rowNo, isset($row->city) ? $row->city : '--' );
                    $activeWorksheet->setCellValue('B'.$rowNo, isset($row->date) ? $row->date->format('d.m.Y H:i') : '--' );
                    $activeWorksheet->setCellValue('C'.$rowNo, isset($row->temp) ? ( number_format($row->temp,1,',') . '°' ) : '--' );
                    $activeWorksheet->setCellValue('D'.$rowNo, isset($row->desc) ? $row->desc : '--' );    
                }

                // generating file and pushing to output
                $writer = new Xlsx($spreadsheet);
                
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename="forecast_' . time() . '.xlsx"');
                $writer->save('php://output');
            }
        }

    }

}