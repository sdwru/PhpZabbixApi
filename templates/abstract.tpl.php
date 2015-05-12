<?php
/**
 * @file        <FILENAME_ABSTRACT>
 *
 * @brief       Class file for the implementation of the class <CLASSNAME_ABSTRACT>.
 *
 * __        ___    ____  _   _ ___ _   _  ____
 * \ \      / / \  |  _ \| \ | |_ _| \ | |/ ___|
 *  \ \ /\ / / _ \ | |_) |  \| || ||  \| | |  _
 *   \ V  V / ___ \|  _ <| |\  || || |\  | |_| |
 *    \_/\_/_/   \_\_| \_\_| \_|___|_| \_|\____|
 *
 * This class was automatically generated by the PhpZabbixApi builder script,
 * and will be replaced as soon as the script is invoked again, or you're about
 * to update the PhpZabbixApi version. Therefor do not modify this file and
 * leave it as it is. After updating / regenerating the library, this file will
 * be overwritten, and all modifications will be lost.
 * If you need to customize the API class, please implement your customizations
 * in the concrete class "<CLASSNAME_CONCRETE>".
 *
 * This file is part of PhpZabbixApi.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @copyright   The MIT License (MIT)
 * @author      confirm IT solutions GmbH, Rathausstrase 14, CH-6340 Baar
 */

namespace ZabbixApi;

/**
 * @brief   Abstract class for the Zabbix API.
 */

abstract class <CLASSNAME_ABSTRACT>
{

    /**
     * @brief   Boolean if requests/responses should be printed out (JSON).
     */

    private $printCommunication = FALSE;

    /**
     * @brief   API URL.
     */

    private $apiUrl = '';

    /**
     * @brief   Default params.
     */

    private $defaultParams = array();

    /**
     * @brief   Auth string.
     */

    private $auth = '';

    /**
     * @brief   Request ID.
     */

    private $id = 0;

    /**
     * @brief   Request array.
     */

    private $request = array();

    /**
     * @brief   JSON encoded request string.
     */

    private $requestEncoded = '';

    /**
     * @brief   JSON decoded response string.
     */

    private $response = '';

    /**
     * @brief   Response object.
     */

    private $responseDecoded = NULL;

    /**
     * @brief   Extra HTTP headers.
     */

    private $extraHeaders = '';

    /**
     * @brief   Class constructor.
     *
     * @param   $apiUrl         API url (e.g. http://FQDN/zabbix/api_jsonrpc.php)
     * @param   $user           Username for Zabbix API.
     * @param   $password       Password for Zabbix API.
     * @param   $httpUser       Username for HTTP basic authorization.
     * @param   $httpPassword   Password for HTTP basic authorization.
     */

    public function __construct($apiUrl='', $user='', $password='', $httpUser='', $httpPassword='')
    {
        if($apiUrl)
            $this->setApiUrl($apiUrl);

        if($user && $password)
            $this->userLogin(array('user' => $user, 'password' => $password));

        if ($httpUser && $httpPassword)
            $this->extraHeaders = 'Authorization: Basic ' . base64_encode($httpUser.':'.$httpPassword);
    }

    /**
     * @brief   Returns the API url for all requests.
     *
     * @retval  string  API url.
     */

    public function getApiUrl()
    {
        return $this->apiUrl;
    }


    /**
     * @brief   Sets the API url for all requests.
     *
     * @param   $apiUrl     API url.
     *
     * @retval  <CLASSNAME_ABSTRACT>
     */

    public function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;
        return $this;
    }

    /**
     * @brief   Returns the default params.
     *
     * @retval  array   Array with default params.
     */

    public function getDefaultParams()
    {
        return $this->defaultParams;
    }

    /**
     * @brief   Sets the default params.
     *
     * @param   $defaultParams  Array with default params.
     *
     * @retval  <CLASSNAME_ABSTRACT>
     *
     * @throws  Exception
     */

    public function setDefaultParams($defaultParams)
    {

        if(is_array($defaultParams))
            $this->defaultParams = $defaultParams;
        else
            throw new \Exception('The argument defaultParams on setDefaultParams() has to be an array.');

        return $this;
    }

    /**
     * @brief   Sets the flag to print communication requests/responses.
     *
     * @param   $print  Boolean if requests/responses should be printed out.
     *
     * @retval  <CLASSNAME_ABSTRACT>
     */
    public function printCommunication($print = TRUE)
    {
        $this->printCommunication = (bool) $print;
        return $this;
    }

    /**
     * @brief   Sends are request to the zabbix API and returns the response
     *          as object.
     *
     * @param   $method     Name of the API method.
     * @param   $params     Additional parameters.
     * @param   $auth       Enable auth string (default TRUE).
     *
     * @retval  stdClass    API JSON response.
     */

    public function request($method, $params=NULL, $resultArrayKey='', $auth=TRUE)
    {

        // sanity check and conversion for params array
        if(!$params)                $params = array();
        elseif(!is_array($params))  $params = array($params);

        // generate ID
        $this->id = number_format(microtime(true), 4, '', '');

        // build request array
        if ($auth)
        {
            $this->request = array(
                'jsonrpc' => '2.0',
                'method'  => $method,
                'params'  => $params,
                'auth'    => ($this->auth ? $this->auth : NULL),
                'id'      => $this->id
            );
        }
        else
        {
            $this->request = array(
                'jsonrpc' => '2.0',
                'method'  => $method,
                'params'  => $params,
                'id'      => $this->id
            );
        }

        // encode request array
        $this->requestEncoded = json_encode($this->request);

        // debug logging
        if($this->printCommunication)
            echo 'API request: '.$this->requestEncoded;

        // do request
        $streamContext = stream_context_create(array('http' => array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/json-rpc'."\r\n".$this->extraHeaders,
            'content' => $this->requestEncoded
        )));

        // get file handler
        $fileHandler = fopen($this->getApiUrl(), 'rb', false, $streamContext);
        if(!$fileHandler)
            throw new \Exception('Could not connect to "'.$this->getApiUrl().'"');

        // get response
        $this->response = @stream_get_contents($fileHandler);

        // debug logging
        if($this->printCommunication)
            echo $this->response."\n";

        // response verification
        if($this->response === FALSE)
            throw new \Exception('Could not read data from "'.$this->getApiUrl().'"');

        // decode response
        $this->responseDecoded = json_decode($this->response);

        // validate response
        if(!is_object($this->responseDecoded) && !is_array($this->responseDecoded))
            throw new \Exception('Could not decode JSON response.');
        if(array_key_exists('error', $this->responseDecoded))
            throw new \Exception('API error '.$this->responseDecoded->error->code.': '.$this->responseDecoded->error->data);

        // return response
        if($resultArrayKey && is_array($this->responseDecoded->result))
            return $this->convertToAssociatveArray($this->responseDecoded->result, $resultArrayKey);
        else
            return $this->responseDecoded->result;
    }

    /**
     * @brief   Returns the last JSON API request.
     *
     * @retval  string  JSON request.
     */

    public function getRequest()
    {
        return $this->requestEncoded;
    }

    /**
     * @brief   Returns the last JSON API response.
     *
     * @retval  string  JSON response.
     */

    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @brief   Convertes an indexed array to an associative array.
     *
     * @param   $indexedArray           Indexed array with objects.
     * @param   $useObjectProperty      Object property to use as array key.
     *
     * @retval  associative Array
     */

    private function convertToAssociatveArray($objectArray, $useObjectProperty)
    {
        // sanity check
        if(count($objectArray) == 0 || !property_exists($objectArray[0], $useObjectProperty))
            return $objectArray;

        // loop through array and replace keys
        foreach($objectArray as $key => $object)
        {
            unset($objectArray[$key]);
            $objectArray[$object->{$useObjectProperty}] = $object;
        }

        // return associative array
        return $objectArray;
    }

    /**
     * @brief   Returns a params array for the request.
     *
     * This method will automatically convert all provided types into a correct
     * array. Which means:
     *
     *      - arrays will not be converted (indexed & associatve)
     *      - scalar values will be converted into an one-element array (indexed)
     *      - other values will result in an empty array
     *
     * Afterwards the array will be merged with all default params, while the
     * default params have a lower priority (passed array will overwrite default
     * params). But there is an exception for merging: If the passed array is an
     * indexed array, the default params will not be merged. This is because
     * there are some API methods, which are expecting a simple JSON array (aka
     * PHP indexed array) instead of an object (aka PHP associative array).
     * Example for this behaviour are delete operations, which are directly
     * expecting an array of IDs '[ 1,2,3 ]' instead of '{ ids: [ 1,2,3 ] }'.
     *
     * @param   $params     Params array.
     *
     * @retval  Array
     */

    private function getRequestParamsArray($params)
    {
        // if params is a scalar value, turn it into an array
        if(is_scalar($params))
            $params = array($params);

        // if params isn't an array, create an empty one (e.g. for booleans, NULL)
        elseif(!is_array($params))
            $params = array();

        // if array isn't indexed, merge array with default params
        if(count($params) == 0 || array_keys($params) !== range(0, count($params) - 1))
            $params = array_merge($this->getDefaultParams(), $params);

        // return params
        return $params;
    }

    /**
     * @brief   Login into the API.
     *
     * This will also retreive the auth Token, which will be used for any
     * further requests.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associatve instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. name, host,
     * hostid, graphid, screenitemid).
     *
     * @param   $params             Parameters to pass through.
     * @param   $arrayKeyProperty   Object property for key of array.
     * @param   $tokenCacheDir      Path to a directory to store / cache login tokens.
     *
     * @retval  stdClass
     *
     * @throws  Exception
     */

    final public function userLogin($params=array(), $arrayKeyProperty='', $tokenCacheDir='/tmp')
    {
        // reset auth token
        $this->auth = '';

        // build filename for cached auth token
        if($tokenCacheDir && array_key_exists('user', $params) && is_dir($tokenCacheDir))
            $tokenCacheFile = $tokenCacheDir.'/.zabbixapi-token-'.$params['user'];

        // try to read cached auth token
        if(isset($tokenCacheFile) && is_file($tokenCacheFile))
        {
            try
            {
                // get auth token and try to execute a user.get (dummy check)
                $this->auth = file_get_contents($tokenCacheFile);
                $this->userGet();
            }
            catch(\Exception as $e)
            {
                // user.get failed, token invalid so reset it and remove file
                $this->auth = '';
                unlink($tokenCacheFile);
            }
        }

        // no cached token found so far, so login (again)
        if(!$this->auth)
        {
            // login to get the auth token
            $params = $this->getRequestParamsArray($params);
            $this->auth = $this->request('user.login', $params, $arrayKeyProperty, FALSE);

            // save cached auth token
            if(isset($tokenCacheFile))
                file_put_contents($tokenCacheFile, $this->auth);
        }


        return $this->auth;
    }

    /**
     * @brief   Logout from the API.
     *
     * This will also reset the auth Token.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associatve instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. name, host,
     * hostid, graphid, screenitemid).
     *
     * @param   $params             Parameters to pass through.
     * @param   $arrayKeyProperty   Object property for key of array.
     *
     * @retval  stdClass
     *
     * @throws  Exception
     */

    final public function userLogout($params=array(), $arrayKeyProperty='')
    {
        $params   = $this->getRequestParamsArray($params);
        $response = $this->request('user.logout', $params, $arrayKeyProperty);
        $this->auth = '';
        return $response;
    }

    <!START_API_METHOD>
    /**
     * @brief   Reqeusts the Zabbix API and returns the response of the API
     *          method <API_METHOD>.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associatve instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. name, host,
     * hostid, graphid, screenitemid).
     *
     * @param   $params             Zabbix API parameters.
     * @param   $arrayKeyProperty   Object property for key of array.
     *
     * @retval  stdClass
     *
     * @throws  Exception
     */

    public function <PHP_METHOD>($params=array(), $arrayKeyProperty='')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // request
        return $this->request('<API_METHOD>', $params, $arrayKeyProperty);
    }
    <!END_API_METHOD>

}

?>
