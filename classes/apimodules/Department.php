<?php namespace Wechat\Classes\ApiModules;

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

    }

    public function deleteDepartment($departmentId)
    {

    }

    public function listDepartments($departmentId = 1)
    {

    }
}
