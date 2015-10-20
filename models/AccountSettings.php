<?php namespace Wechat\Models;

use Model;

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

}
