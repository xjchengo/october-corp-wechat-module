<?php namespace Wechat\Classes\ApiModules\Contacts;

use Illuminate\Support\Fluent;
use October\Rain\Support\Collection;

trait Department
{
    public function createDepartment($name, $parentId = 1, $order = null, $departmentId = null)
    {
        $data = [
            'name' => $name,
            'parentid' => $parentId,
            'order' => $order,
            'departmentId' => $departmentId,
        ];

        $result = $this->httpPost(static::DEPARTMENT_CREATE_URL, $data);

        if (!$this->processWechatApiResult(($result))) {
            return false;
        }

        return $result->id;
    }

    public function updateDepartment($departmentId, $data)
    {
        $data['id'] = $departmentId;

        $result = $this->httpPost(static::DEPARTMENT_UPDATE_URL, $data);

        if (!$this->processWechatApiResult(($result))) {
            return false;
        }

        return true;
    }

    public function deleteDepartment($departmentId)
    {
        $query['id'] = $departmentId;

        $result = $this->httpGet(static::DEPARTMENT_DELETE_URL, $query);

        if (!$this->processWechatApiResult(($result))) {
            return false;
        }

        return true;
    }

    public function listDepartments($departmentId = 1)
    {
        $query['id'] = $departmentId;

        $result = $this->httpGet(static::DEPARTMENT_LIST_URL, $query);

        if (!$this->processWechatApiResult(($result))) {
            return false;
        }

        return (new Collection($result->department))->map(function($department) {
            return new Fluent($department);
        });
    }
}
