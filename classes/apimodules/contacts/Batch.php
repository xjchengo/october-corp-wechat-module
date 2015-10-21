<?php namespace Wechat\Classes\ApiModules\Contacts;

use Illuminate\Support\Fluent;
use Wechat\Classes\Collection;

trait Batch
{
    public function batchInvite($userList = [], $departmentList = [], $tagList = [], $callback = null)
    {
        if (is_string($userList)) {
            $data['touser'] = $userList;
        } elseif ($userList) {
            $data['touser'] = implode('|', (array)$userList);
        }
        if (is_string($departmentList)) {
            $data['toparty'] = $departmentList;
        } elseif ($departmentList) {
            $data['toparty'] = implode('|', (array)$departmentList);
        }
        if (is_string($tagList)) {
            $data['totag'] = $tagList;
        } elseif ($tagList) {
            $data['totag'] = implode('|', (array)$tagList);
        }
        $data['callback'] = $callback;

        $result = $this->httpPost(static::BATCH_INVITE_URL, $data);

        if (!$this->processWechatApiResult(($result))) {
            return false;
        }

        return $result;
    }

    public function syncUsers($filename, $callback = null)
    {
        return $this->batchOperate(static::BATCH_SYNCUSER_URL, $filename, $callback);
    }

    public function replaceUsers($filename, $callback = null)
    {
        return $this->batchOperate(static::BATCH_REPLACEUSER_URL, $filename, $callback);
    }

    public function replaceDepartments($filename, $callback = null)
    {
        return $this->batchOperate(static::BATCH_REPLACEPARTY_URL, $filename, $callback);
    }

    protected function batchOperate($url, $filename, $callback = null)
    {
        $media = $this->uploadMedia('file', $filename);
        $data = [
            'media_id' => $media->media_id,
            'callback' => $callback,
        ];

        $result = $this->httpPost($url, $data);

        if (!$this->processWechatApiResult(($result))) {
            return false;
        }

        return $result;
    }
}
