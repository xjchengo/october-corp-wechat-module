<?php namespace Wechat\Classes\ApiModules;

use Wechat\Classes\ApiModules\Contacts\Batch;
use Wechat\Classes\ApiModules\Contacts\Department;
use Wechat\Classes\ApiModules\Contacts\Tag;
use Wechat\Classes\ApiModules\Contacts\User;

trait Contacts
{
    use Batch, Department, Tag, User;
}
