<?php namespace Wechat\Classes;

use Cache;
use Http;
use Illuminate\Support\Fluent;
use InvalidArgumentException;
use Wechat\Classes\ApiModules\Department;

class WechatApi implements WechatApiInterface
{
    use Department;

    protected $corpId;
    protected $secret;
    protected $accessToken;

    protected $errCode;
    protected $errMsg;

    public function __construct($corpId, $secret)
    {
        $this->corpId = $corpId;
        $this->secret = $secret;
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

        $result = $this->httpGet(static::TOKEN_GET_URL, function($http) {
            $http->data([
                'corpid' => $this->corpId,
                'corpsecret' => $this->secret,
            ]);
        }, false);

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

    public function httpGet($url, $query = [], $options = null, $appendAccessToken = true, $jsonDecode = true)
    {
        $url = $this->processUrl($url, $appendAccessToken, $query);

        $result = Http::get($url, $options);

        return $this->processHttpResult($result, $jsonDecode);
    }

    public function httpPost($url, $data = [], $options = null, $appendAccessToken = true, $jsonDecode = true)
    {
        $url = $this->processUrl($url, $appendAccessToken);
        $data = $this->filterData($data);

        $result = Http::post($url, function($http) use($data, $options) {
            $http->data($data);
            if ($options && is_callable($options)) $options($http);
        });

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
            $resultArray = json_decode((string)$result, true);
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
        $caller = debug_backtrace(false, 2)[1];
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
