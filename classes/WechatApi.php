<?php namespace Wechat\Classes;

use Cache;
use Http;
use Illuminate\Support\Fluent;
use InvalidArgumentException;
use Wechat\Classes\ApiModules\Contacts;
use GuzzleHttp\ClientInterface as HttpClientInterface;
use GuzzleHttp\Client as HttpClient;
use Wechat\Classes\ApiModules\Media;

class WechatApi implements WechatApiInterface
{
    use Contacts,
        Media;

    protected $corpId;
    protected $secret;
    protected $httpClient;

    protected $accessToken;

    protected $errCode;
    protected $errMsg;

    public function __construct($corpId, $secret, HttpClientInterface $httpClient = null)
    {
        $this->corpId = $corpId;
        $this->secret = $secret;
        if (!$httpClient) {
            $httpClient = new HttpClient([
                'base_uri' => static::API_URL_PREFIX,
            ]);
        }
        $this->httpClient = $httpClient;

        if (!$this->getAccessToken()) {
            throw new InvalidArgumentException('企业号信息不正确，请到后台设置中确认账号信息。');
        }
    }

    public function getAccessToken()
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        if ($cached = $this->getCached('access_token')) {
            return $this->accessToken = $cached;
        }

        $result = $this->httpGet(static::TOKEN_GET_URL, [
            'corpid' => $this->corpId,
            'corpsecret' => $this->secret,
        ], [], false);

        if (!$this->processWechatApiResult(($result))) {
            return false;
        }

        $this->cache('access_token', $result->access_token, $result->expires_in);

        return $this->accessToken = $result->access_token;
    }

    protected function getCached($keyName)
    {
        $cacheKey = $this->getCacheKey($keyName);

        return Cache::get($cacheKey);
    }

    protected function cache($keyName, $value, $seconds)
    {
        $cacheKey = $this->getCacheKey($keyName);

        Cache::put($cacheKey, $value, (int)floor($seconds/60));
    }

    protected function getCacheKey($keyName)
    {
        return 'Wechat::' . $this->corpId . '.' .$keyName;
    }

    public function httpGet($url, $query = [], $options = [], $appendAccessToken = true, $jsonDecode = true)
    {
        $url = $this->processUrl($url, $appendAccessToken, $query);

        $result = $this->httpClient->get($url, $options);

        return $this->processHttpResult($result, $jsonDecode);
    }

    public function httpPost($url, $data = [], $options = [], $appendAccessToken = true, $jsonDecode = true)
    {
        $url = $this->processUrl($url, $appendAccessToken);
        if ($data !== null) {
            $data = $this->filterData($data);
            $options['json'] = $data;
        }
        $result = $this->httpClient->post($url, $options);

        return $this->processHttpResult($result, $jsonDecode);
    }

    protected function processUrl($url, $appendAccessToken = true, $query = [])
    {
        if (!starts_with($url, ['http://', 'https://'])) {
            $url = static::API_URL_PREFIX . $url;
        }

        if ($appendAccessToken) {
            $query['access_token'] = $this->getAccessToken();
        }

        if (!$query) {
            return $url;
        }

        if (strstr($url, '?') !== false) {
            $url .= '&' . http_build_query($query);
        } else {
            $url .= '?' . http_build_query($query);
        }

        return $url;
    }

    protected function processHttpResult($result, $jsonDecode = true)
    {
        if ($jsonDecode) {
            $resultArray = json_decode((string)$result->getBody(), true);
            return new Fluent($resultArray);
        }

        return $result;
    }

    protected function processWechatApiResult(Fluent $result)
    {
        if (isset($result->errcode) and $result->errcode != 0) {
            $this->errCode = $result->errcode;
            $this->errMsg = $result->errmsg;
            $this->logError();

            return false;
        }

        return $result;
    }

    protected function logError()
    {
        $caller = debug_backtrace(false, 3)[2];
        $errorDetails = [
            'corpId' => $this->corpId,
            'secret' => $this->secret,
            'caller' => $caller,
            'errCode' => $this->errCode,
            'errMsg' => $this->errMsg,
        ];
        traceLog($errorDetails, 'error');
    }

    protected function jsonEncode($data)
    {
        return json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    }

    protected function filterData($data)
    {
        return array_filter($data, function($value) {
            return $value !== null;
        });
    }

    public function getErrCode()
    {
        return $this->errCode;
    }

    public function getErrMsg()
    {
        return $this->errMsg;
    }

}
