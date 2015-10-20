<?php namespace Wechat;

use October\Rain\Support\ModuleServiceProvider;
use System\Classes\SettingsManager;
use Wechat\Models\AccountSettings;
use InvalidArgumentException;
use Wechat\Classes\WechatApi;
use Illuminate\Foundation\AliasLoader;

class ServiceProvider extends ModuleServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        parent::register('wechat');

        $this->registerBackendSettings();
        $this->registerWechatApi();
    }

    /**
     * Bootstrap the module events.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot('wechat');

        $this->bootWechatApi();
    }

    public function registerBackendSettings()
    {
        SettingsManager::instance()->registerCallback(function ($manager) {
            $manager->registerSettingItems('Huying.Wechat', [
                'account' => [
                    'label'       => '微信账号设置',
                    'description' => '在这里设置企业号的相关信息',
                    'category'    => SettingsManager::CATEGORY_SYSTEM,
                    'icon'        => 'icon-cloud-download',
                    'class'       => 'Wechat\Models\AccountSettings',
                    'permissions' => ['wechat.manage_account'],
                    'order'       => 300
                ],
            ]);
        });
    }

    public function registerWechatApi()
    {
        $this->app['wechat.api'] = $this->app->share(function($app) {
            $corpId = AccountSettings::get('corp_id');
            $secret = AccountSettings::get('secret');
            return new WechatApi($corpId, $secret);
        });
    }

    public function bootWechatApi()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('WechatApi', 'Wechat\Facades\WechatApi');
    }

}
