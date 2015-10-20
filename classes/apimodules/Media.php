<?php namespace Wechat\Classes\ApiModules;

use Symfony\Component\HttpFoundation\File\File;

trait Media
{
    public function uploadMedia($type, $path)
    {
        $query = [
            'type' => $type,
        ];
        $url = $this->processUrl(static::MEDIA_UPLOAD_URL, true, $query);

        $options = [
            'multipart' => [
                [
                    'name'     => 'media',
                    'contents' => fopen($path, 'r')
                ],
            ]
        ];
        $result = $this->httpPost($url, null, $options, false);

        if (!$this->processWechatApiResult(($result))) {
            return false;
        }

        return $result;
    }

    public function getMedia($mediaId)
    {
        $query = [
            'media_id' => $mediaId,
        ];

        $result = $this->httpGet(static::MEDIA_GET_URL, $query, [], true, false);

        $contentType = $result->getHeader('Content-Type')[0];
        if (strpos($contentType, 'application/json') !== false) {
            $result = $this->processHttpResult($result);
            return $this->processWechatApiResult($result);
        }

        $contentDisposition = $result->getHeader('Content-disposition')[0];
        preg_match('#filename="(.*)"#', $contentDisposition, $matches);
        $filename = $matches[1];
        $tempDir = temp_path(uniqid());
        if (!is_dir($tempDir)) {
            mkdir($tempDir);
        }
        $tempPath = $tempDir.'/'.$filename;
        file_put_contents($tempPath, $result->getBody());

        return new File($tempPath);
    }
}
