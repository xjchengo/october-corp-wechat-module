<?php namespace Wechat\Models;

use Model;
use Wechat\Classes\WechatApi;
use October\Rain\Exception\ApplicationException;

/**
 * Wechat account settings
 */
class AccountSettings extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'wechat_account_settings';

    public $settingsFields = 'fields.yaml';

    /**
     * Validation rules
     */
    public $rules = [
        'corp_id' => 'required',
        'secret' => 'required',
    ];

    public function afterValidate()
    {
        $api = new WechatApi($this->corp_id, $this->secret);
        $api->clearCachedAccessToken();
        if (!$api->getAccessToken()) {
            throw new ApplicationException('企业号信息不正确，请到后台设置中确认账号信息。');
        }
    }

}
