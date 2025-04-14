<?php namespace TwoSolar\Handler;

/**
 *  Class to deal with the REST API
 */
final class SolarRestApi
{

    /**
     *  CONST TWOSOLOR_API_KEY
     *
     *  The access token
     *  @var string
     */

    /**
     *  CONST TWOSOLOR_API_URL
     *
     *  Url for API
     *  @var string
     */


    /**
     *  Constructor
     *  @param  string      Access token
     */
    public function __construct()
    {
    }

    //----------------------------------------------------------------------------------------

    /**
     * Init Curl
     * @param  string      Resource to fetch
     * @param  array       Not Associative array with additional URI parameters
     * @return Object
     */
    public function curlConstruct(string $resource, array $parameters = [])
    {
        $uri = '';
        if (count($parameters) > 0) {
            $uri = implode("/", array_values($parameters));
        }

        // heeft alleen invloed op (oude) quotes, wordt in in person request ook meegenomen,maar doet niets
        // $uri .= ($uri!=''?"&":"?") . "quote_created_from=".date('Y-m-d', strtotime('-30 days'));

        // construct curl resource
        return curl_init(TWOSOLOR_API_URL . $resource . ($uri!=''?"/":"") . $uri);
    }

    //----------------------------------------------------------------------------------------

    /**
     *  Do a GET request
     *  @param  string      Resource to fetch
     *  @param  array       NOT associative array with additional URI parameters
     *  @param  bool        return array or json answer
     *  @return array       Associative array with the result
     */
    public function get(string $resource, array $parameters = [], bool $raw = false)
    {
        $curl = $this->curlConstruct($resource, $parameters);

        // additional options
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => true,
            //CURLOPT_VERBOSE => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => [
                'Content-Type:application/json',
                'Authorization: Bearer ' . TWOSOLOR_API_KEY
            ],
        ));

        // do the call
        $answer = curl_exec($curl);

        // clean up curl resource
        curl_close($curl);

        // done
        if (!$raw) {
            return json_decode($answer, true);
        } else {
            return $answer;
        }
    }

    //----------------------------------------------------------------------------------------
}
