<?php namespace Wechat\Classes;

interface WechatApiInterface
{
    const MSGTYPE_TEXT 		= 'text';
    const MSGTYPE_IMAGE 	= 'image';
    const MSGTYPE_LOCATION 	= 'location';
    const MSGTYPE_LINK 		= 'link';    	//暂不支持
    const MSGTYPE_EVENT 	= 'event';
    const MSGTYPE_MUSIC 	= 'music';    	//暂不支持
    const MSGTYPE_NEWS 		= 'news';
    const MSGTYPE_VOICE 	= 'voice';
    const MSGTYPE_VIDEO 	= 'video';

    const EVENT_SUBSCRIBE 	= 'subscribe';      //订阅
    const EVENT_UNSUBSCRIBE = 'unsubscribe'; 	//取消订阅
    const EVENT_LOCATION 	= 'LOCATION';       //上报地理位置
    const EVENT_ENTER_AGENT = 'enter_agent';   	//用户进入应用

    const EVENT_MENU_VIEW 			= 'VIEW'; 				//菜单 - 点击菜单跳转链接
    const EVENT_MENU_CLICK 			= 'CLICK';              //菜单 - 点击菜单拉取消息
    const EVENT_MENU_SCAN_PUSH 		= 'scancode_push';      //菜单 - 扫码推事件(客户端跳URL)
    const EVENT_MENU_SCAN_WAITMSG 	= 'scancode_waitmsg'; 	//菜单 - 扫码推事件(客户端不跳URL)
    const EVENT_MENU_PIC_SYS 		= 'pic_sysphoto';       //菜单 - 弹出系统拍照发图
    const EVENT_MENU_PIC_PHOTO 		= 'pic_photo_or_album'; //菜单 - 弹出拍照或者相册发图
    const EVENT_MENU_PIC_WEIXIN 	= 'pic_weixin';         //菜单 - 弹出微信相册发图器
    const EVENT_MENU_LOCATION 		= 'location_select';    //菜单 - 弹出地理位置选择器

    const EVENT_SEND_MASS = 'MASSSENDJOBFINISH';        //发送结果 - 高级群发完成
    const EVENT_SEND_TEMPLATE = 'TEMPLATESENDJOBFINISH';//发送结果 - 模板消息发送结果

    const API_URL_PREFIX = 'https://qyapi.weixin.qq.com/cgi-bin';

    const USER_CREATE_URL 		= '/user/create';
    const USER_UPDATE_URL 		= '/user/update';
    const USER_DELETE_URL 		= '/user/delete';
    const USER_BATCHDELETE_URL 	= '/user/batchdelete';
    const USER_GET_URL 			= '/user/get';
    const USER_LIST_URL 		= '/user/simplelist';
    const USER_LIST_INFO_URL 	= '/user/list';
    const USER_GETINFO_URL 		= '/user/getuserinfo';
    const USER_INVITE_URL 		= '/invite/send';
    const DEPARTMENT_CREATE_URL = '/department/create';
    const DEPARTMENT_UPDATE_URL = '/department/update';
    const DEPARTMENT_DELETE_URL = '/department/delete';
    const DEPARTMENT_MOVE_URL 	= '/department/move';
    const DEPARTMENT_LIST_URL 	= '/department/list';
    const TAG_CREATE_URL 		= '/tag/create';
    const TAG_UPDATE_URL 		= '/tag/update';
    const TAG_DELETE_URL 		= '/tag/delete';
    const TAG_GET_URL 			= '/tag/get';
    const TAG_ADDUSER_URL 		= '/tag/addtagusers';
    const TAG_DELUSER_URL 		= '/tag/deltagusers';
    const TAG_LIST_URL 			= '/tag/list';
    const MEDIA_UPLOAD_URL 		= '/media/upload';
    const MEDIA_GET_URL 		= '/media/get';
    const MATERIAL_UPLOAD_URL 		= '/material/add_material';
    const AUTHSUCC_URL 			= '/user/authsucc';
    const MASS_SEND_URL 		= '/message/send';
    const MENU_CREATE_URL 		= '/menu/create';
    const MENU_GET_URL 			= '/menu/get';
    const MENU_DELETE_URL 		= '/menu/delete';
    const TOKEN_GET_URL 		= '/gettoken';
    const TICKET_GET_URL 		= '/get_jsapi_ticket';
    const CALLBACKSERVER_GET_URL = '/getcallbackip';
    const OAUTH_PREFIX 			= 'https://open.weixin.qq.com/connect/oauth2';
    const OAUTH_AUTHORIZE_URL 	= '/authorize';
}
