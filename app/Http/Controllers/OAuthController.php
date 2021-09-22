<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class OAuthController extends Controller
{
    //
    public function index(){
        $_SESSION['state'] = rand(0,999999999);
        $authorizationUrlBase = 'https://accounts.google.com/o/oauth2/auth';
        $redirectUriPath = '/oauth/my_callback';

// For example only.  A valid value for client_id needs to be obtained
// for your environment from the Google APIs Console at
// http://code.google.com/apis/console.
        $queryParams = array(
            'client_id' => '476593248064-bvmn6iu94dntqihg5jgbh7l95engkafl.apps.googleusercontent.com',
            'redirect_uri' => (isset($_SERVER['HTTPS'])?'https://':'http://') .
                $_SERVER['HTTP_HOST'] . $redirectUriPath,
            'scope' => 'https://www.googleapis.com/auth/spreadsheets.readonly',
            'response_type' => 'code',
            'state' => $_SESSION['state'],
            'approval_prompt' => 'force', // always request user consent
            'access_type' => 'offline' // obtain a refresh token
        );

        $goToUrl = $authorizationUrlBase . '?' . http_build_query($queryParams);

        return $goToUrl;
    }

    public function callBack(){

        $code = $_GET['code'];
        $accessTokenExchangeUrl = 'https://accounts.google.com/o/oauth2/token';
        $redirectUriPath = '/oauth/my_callback';

// For example only.  Valid values for client_id and client_secret
// need to be obtained for your environment from the Google APIs
// Console at http://code.google.com/apis/console.
// Also, these values should not be hard-coded in a production application.
// Instead, they should be loaded in from a configuration file or secure keystore.
        $accessTokenExchangeParams = array(
            'client_id' => '476593248064-bvmn6iu94dntqihg5jgbh7l95engkafl.apps.googleusercontent.com',
            'client_secret' => 'jSY2XpMj9yclC4MZ9O5TafUg',
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => (isset($_SERVER['HTTPS'])?'https://':'http://') .
                $_SERVER['HTTP_HOST'] . $redirectUriPath
        );


        $curl =curl_init($accessTokenExchangeUrl);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$accessTokenExchangeParams);
        $response = curl_exec($curl);
        curl_close($curl);



//        $httpClient = new Client();
//        $responseJson = $httpClient->postData(
//            $accessTokenExchangeUrl,
//            $accessTokenExchangeParams);
        $responseArray = json_decode($response, TRUE);

        $accessToken = $responseArray['access_token'];
        $expiresIn = $responseArray['expires_in']; //second
        $refreshToken = $responseArray['refresh_token'];

        $_SESSION['access_token'] = $accessToken;
// Storing refresh token in the session, and using approval_prompt=force for
// simplicity. Typically the fresh token would be stored in a server-side database
// and associated with the user's account. This would eliminate the need for
// prompting the user for approval each time.
        $_SESSION['refresh_token'] = $refreshToken;

        $this->callApi($accessToken);

        return $accessToken;
    }

    /** get document
     * @param $accessToken
     */
    private function callApi($accessToken) {
        $sheetUrl = 'https://sheets.googleapis.com/v4/spreadsheets/1-HMCrRIlIj5jrROURnp-xZUFAAI_sLttHjAhcQCVMFM?access_token='.$accessToken;
        $curl =curl_init($sheetUrl);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        $response = curl_exec($curl);
        curl_close($curl);

        $response =json_decode($response,true);

    }
}
