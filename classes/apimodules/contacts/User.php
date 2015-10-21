<?php namespace Wechat\Classes\ApiModules\Contacts;

use Illuminate\Support\Fluent;
use Wechat\Classes\Collection;

trait User
{
    public function createUser($userId, $name, $data, $departmentList = [1])
    {
        $data['userid'] = $userId;
        $data['name'] = $name;
        if (!isset($data['department'])) {
            $data['department'] = (array)$departmentList;
        } else {
            $data['department'] = (array)$data['department'];
        }

        $result = $this->httpPost(static::USER_CREATE_URL, $data);

        if (!$this->processWechatApiResult(($result))) {
            return false;
        }

        return true;
    }

    public function updateUser($userId, $data)
    {
        $data['userid'] = $userId;

        $result = $this->httpPost(static::USER_UPDATE_URL, $data);

        if (!$this->processWechatApiResult(($result))) {
            return false;
        }

        return true;
    }

    public function deleteUsers($userIds)
    {
        $data = [
            'useridlist' => (array) $userIds,
        ];

        $result = $this->httpPost(static::USER_BATCHDELETE_URL, $data);

        if (!$this->processWechatApiResult(($result))) {
            return false;
        }

        return true;
    }

    public function getUser($userId)
    {
        $query = [
            'userid' => $userId,
        ];

        $result = $this->httpGet(static::USER_GET_URL, $query);

        if (!$this->processWechatApiResult(($result))) {
            return false;
        }

        return $result;
    }

    public function listSimpleDepartmentUsers($departmentId, $fetchChild = false, $status = self::USER_STATUS_UNSUBSCRIBED)
    {
        return $this->listDepartmentUsers(true, $departmentId, $fetchChild, $status);
    }

    public function listDetailedDepartmentUsers($departmentId, $fetchChild = false, $status = self::USER_STATUS_UNSUBSCRIBED)
    {
        return $this->listDepartmentUsers(false, $departmentId, $fetchChild, $status);
    }

    protected function listDepartmentUsers($isSimple, $departmentId, $fetchChild = false, $status = self::USER_STATUS_UNSUBSCRIBED)
    {
        $query = [
            'department_id' => $departmentId,
            'fetch_child' => $fetchChild,
            'status' => $status,
        ];

        if ($isSimple) {
            $url = static::USER_LIST_URL;
        } else {
            $url = static::USER_LIST_INFO_URL;
        }

        $result = $this->httpGet($url, $query);

        if (!$this->processWechatApiResult(($result))) {
            return false;
        }

        return (new Collection($result->userlist))->map(function($user) {
            return new Fluent($user);
        });
    }

    public function inviteUser($userId)
    {
        $data = [
            'userid' => $userId,
        ];

        $result = $this->httpPost(static::USER_INVITE_URL, $data);

        if (!$this->processWechatApiResult(($result))) {
            return false;
        }

        return $result->type;
    }
}
