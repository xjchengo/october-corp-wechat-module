<?php namespace Wechat\Classes\ApiModules\Contacts;

use Illuminate\Support\Fluent;
use Wechat\Classes\Collection;

trait Tag
{
    public function createTag($tagName, $tagId = null)
    {
        $data['tagname'] = $tagName;
        $data['tagid'] = $tagId;

        $result = $this->httpPost(static::TAG_CREATE_URL, $data);

        if (!$this->processWechatApiResult(($result))) {
            return false;
        }

        return $result->tagid;
    }

    public function updateTag($tagId, $tagName)
    {
        $data['tagname'] = $tagName;
        $data['tagid'] = $tagId;

        $result = $this->httpPost(static::TAG_UPDATE_URL, $data);

        if (!$this->processWechatApiResult(($result))) {
            return false;
        }

        return true;
    }

    public function deleteTag($tagId)
    {
        $query['tagid'] = $tagId;

        $result = $this->httpGet(static::TAG_DELETE_URL, $query);

        if (!$this->processWechatApiResult(($result))) {
            return false;
        }

        return true;
    }

    public function getTag($tagId)
    {
        $query['tagid'] = $tagId;

        $result = $this->httpGet(static::TAG_GET_URL, $query);

        if (!$this->processWechatApiResult(($result))) {
            return false;
        }

        return $result;
    }

    public function addTagUsers($tagId, $userList)
    {
        return $this->addTagUsersAndDepartments($tagId, $userList);
    }

    public function addTagDepartments($tagId, $departmentList)
    {
        return $this->addTagUsersAndDepartments($tagId, null, $departmentList);
    }

    public function addTagUsersAndDepartments($tagId, $userList = null, $departmentList = null)
    {
        $data['tagid'] = $tagId;
        $data['userlist'] = $userList;
        $data['partylist'] = $departmentList;

        $result = $this->httpPost(static::TAG_ADDUSER_URL, $data);

        if (!$this->processWechatApiResult(($result))) {
            return false;
        }

        return $result;
    }

    public function deleteTagUsers($tagId, $userList)
    {
        return $this->deleteTagUsersAndDepartments($tagId, $userList);
    }

    public function deleteTagDepartments($tagId, $departmentList)
    {
        return $this->deleteTagUsersAndDepartments($tagId, null, $departmentList);
    }

    public function deleteTagUsersAndDepartments($tagId, $userList = null, $departmentList = null)
    {
        $data['tagid'] = $tagId;
        $data['userlist'] = $userList;
        $data['partylist'] = $departmentList;

        $result = $this->httpPost(static::TAG_DELUSER_URL, $data);

        if (!$this->processWechatApiResult(($result))) {
            return false;
        }

        return $result;
    }

    public function listTags()
    {
        $result = $this->httpGet(static::TAG_LIST_URL);

        if (!$this->processWechatApiResult(($result))) {
            return false;
        }

        return (new Collection($result->taglist))->map(function($tag) {
            return new Fluent($tag);
        });
    }
}
