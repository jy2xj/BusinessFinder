<?php

/**
 * Uses Yelp Fusion API
 *
 * This program demonstrates the capability of the Yelp Fusion API
 * by using the Business Search API to query for businesses, and 
 * the Business API to query additional 
 * information about the results from the search query.
 * 
 * Please refer to http://www.yelp.com/developers/v3/documentation 
 * for the API documentation.
 * 
 */

// OAuth credential placeholders that must be filled in by users.
// You can find them on
// https://www.yelp.com/developers/v3/manage_app
// RUN ON HEROKU, SO SET ID AND SECRET AS CONFIG VARIABLES ON HEROKU
$CLIENT_ID = getenv('ID');
$CLIENT_SECRET = getenv('SECRET');

// API constants, you shouldn't have to change these.
$API_HOST = "https://api.yelp.com";
$SEARCH_PATH = "/v3/businesses/search";
$BUSINESS_PATH = "/v3/businesses/";  // Business ID will come after slash.
$TOKEN_PATH = "/oauth2/token";
$GRANT_TYPE = "client_credentials";

/**
 * Given a bearer token, send a GET request to the API.
 * 
 * @return   OAuth bearer token, obtained using client_id and client_secret.
 */

function obtain_bearer_token() {
    try {
        # Using the built-in cURL library for easiest installation.
        # Extension library HttpRequest would also work here.
        $curl = curl_init();
        if (FALSE === $curl)
            throw new Exception('Failed to initialize');

        $postfields = "client_id=" . $GLOBALS['CLIENT_ID'] .
            "&client_secret=" . $GLOBALS['CLIENT_SECRET'] .
            "&grant_type=" . $GLOBALS['GRANT_TYPE'];

        curl_setopt_array($curl, array(
            CURLOPT_URL => $GLOBALS['API_HOST'] . $GLOBALS['TOKEN_PATH'],
            CURLOPT_RETURNTRANSFER => true,  // Capture response.
            CURLOPT_ENCODING => "",  // Accept gzip/deflate/whatever.
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded",
            ),
        ));

        $response = curl_exec($curl);

        if (FALSE === $response)
            throw new Exception(curl_error($curl), curl_errno($curl));
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if (200 != $http_status)
            throw new Exception($response, $http_status);

        curl_close($curl);
    } catch(Exception $e) {
        trigger_error(sprintf(
            'Curl failed with error #%d: %s',
            $e->getCode(), $e->getMessage()),
            E_USER_ERROR);
    }

    $body = json_decode($response);
    $bearer_token = $body->access_token;
    return $bearer_token;
}

/** 
 * Makes a request to the Yelp API and returns the response
 * 
 * @param    $bearer_token   API bearer token from obtain_bearer_token
 * @param    $host    The domain host of the API 
 * @param    $path    The path of the API after the domain.
 * @param    $url_params    Array of query-string parameters.
 * @return   The JSON response from the request      
 */
function request($bearer_token, $host, $path, $url_params = array()) {
    // Send Yelp API Call
    try {
        $curl = curl_init();
        if (FALSE === $curl)
            throw new Exception('Failed to initialize');

        $url = $host . $path . "?" . http_build_query($url_params);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,  // Capture response.
            CURLOPT_ENCODING => "",  // Accept gzip/deflate/whatever.
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . $bearer_token,
                "cache-control: no-cache",
            ),
        ));

        $response = curl_exec($curl);

        if (FALSE === $response)
            throw new Exception(curl_error($curl), curl_errno($curl));
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if (200 != $http_status)
            throw new Exception($response, $http_status);

        curl_close($curl);
    } catch(Exception $e) {
        trigger_error(sprintf(
            'Curl failed with error #%d: %s',
            $e->getCode(), $e->getMessage()),
            E_USER_ERROR);
    }

    return $response;
}

/**
 * Query the Search API by an array 
 * 
 * @param    $bearer_token   API bearer token from obtain_bearer_token
 * @param    $search_arr     The search arr passed from the POST
 * @return   The JSON response from the request 
 */
function search($bearer_token, $search_arr) {
    $url_params = array();
    
    //$url_params['term'] = $term;
    //$url_params['location'] = $location;
    //$url_params['limit'] = $GLOBALS['SEARCH_LIMIT'];
    $url_params['term'] = $search_arr["term"];
    if ($search_arr["curr_loc"] == true) {
        $pieces = explode(",", $search_arr["location"]);
        $url_params['latitude'] = $pieces[0];
        $url_params['longitude'] = $pieces[1];
    }
    else {
        $url_params['location'] = $search_arr["location"];
    }
    if ($search_arr["open"] == true) {
        $url_params['open_now'] = true;
    }
    else {
        //$url_params['open_at'] = (int) $search_arr["time"];
        //echo $url_params['open_at'];
        //$url_params['open_at'] = 1491040800;
        $url_params['open_at'] = 1491049800;
    }
    // $url_params['latitude'] = 38.0295652;
    // $url_params['longitude'] = -78.5046978;
    // $url_params['open_now'] = false;
    // $url_params['open_at'] = 1491040800;
    // $url_params['price'] = "1,2";
    if ($search_arr == "4") $url_params['price'] = "1,2,3,4";
    else if ($search_arr == "3") $url_params['price'] = "1,2,3";
    else if ($search_arr == "2") $url_params['price'] = "1,2";
    else $url_params['price'] = "1";

    
    return request($bearer_token, $GLOBALS['API_HOST'], $GLOBALS['SEARCH_PATH'], $url_params);
}

/**
 * Query the Business API by business_id
 * 
 * @param    $bearer_token   API bearer token from obtain_bearer_token
 * @param    $business_id    The ID of the business to query
 * @return   The JSON response from the request 
 */
function get_business($bearer_token, $business_id) {
    $business_path = $GLOBALS['BUSINESS_PATH'] . urlencode($business_id);
    
    return request($bearer_token, $GLOBALS['API_HOST'], $business_path);
}

function find_best_restaurants($bearer_token, $response) {
    $num = 0;
    $total = count($response->businesses);
    $i = 0;
    $arr = array();
    // goes until 3 businesses or total
    while ($num < 3 and $i < $total) {
        $business_id = $response->businesses[$i]->id;
        $business = get_business($bearer_token, $business_id);
        $business_json = json_decode($business,true);
        array_push($arr, array($business_json["name"],$business_json["url"],$business_json["image_url"],$business_json["price"],
            $business_json["location"]["display_address"][0],$business_json["location"]["display_address"][1],
            $business_json["phone"], $business_json["coordinates"]["latitude"], $business_json["coordinates"]["longitude"])); 
        $i++;
        $num++;
    }
    echo json_encode($arr);
}

/**
 * Queries the API by the post data from the user 
 * 
 */
function query_api() {     
    $bearer_token = obtain_bearer_token();

    $arr = json_decode($_POST['search_data'], true);
    $response = json_decode(search($bearer_token, $arr));
    //$business_id = $response->businesses[0]->id;
    
    //$response = get_business($bearer_token, $business_id);
    //echo json_decode($response,true)["display_phone"];
    //echo $business_id;
    find_best_restaurants($bearer_token, $response);
    /*echo sprintf(
        "%d businesses found, querying business info for the top result \"%s\"\n\n",         
        count($response->businesses),
        $business_id
    );
    
    $response = get_business($bearer_token, $business_id);
    
    echo sprintf("Result for business \"%s\" found:\n", $business_id);
    $pretty_response = json_encode(json_decode($response), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    echo "$pretty_response\n";*/
}

/**
 * User input is handled here 
 */
/*$longopts  = array(
    "term::",
    "location::",
);
    
$options = getopt("", $longopts);*/

query_api();

?>