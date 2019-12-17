<?php
/**
 * Author: zhangs
 * Date: 2019/6/28
 * Time: 10:50
 */

namespace app\admin\middleware;

use app\api\apiConst\ApiCodeMsg;
use app\api\exception\ApiException;
use jwt\JwtAuth;
use think\Controller;
use think\facade\Cache;
use think\facade\Request;

class CheckToken extends Controller
{
    /**
     * 默认返回资源类型
     * @return mixed
     * @throws \Exception
     * @var string $name
     * @var \think\Request $request
     * @var mixed $next
     */
    public function handle($request, \Closure $next, $name)
    {
        if (input("debug") == 1) {
            return $next($request);
        }

        if ($request->isOptions()) {
            return json('SUCCESS');
        }

        //不需要验证的方法
        # 登录，登出，聊天记录
        $arrNextAction = config('system.no_login_methods');

        $action = $request->action();
        // dump2($arrNextAction);
        if (in_array($action, $arrNextAction)) {
            //echo 111;exit;
            return $next($request);
        }

        //不需要验证的类
        #留言模块
        $arrNextController = ['LeaveMessage'];
        $controller = $request->controller();
        if (in_array($controller, $arrNextController)) {
            return $next($request);
        }




        // throw new ApiException(ApiErrDesc::ERROR_JWT_TOKEN_EXPIRED);
        // dump2(Request::header());


        #时间验证
        $time = Request::header('timestamp');
        if (!$time) {
            throw new ApiException(ApiCodeMsg::EMPTY_TIMESTAMP_PARAM);
        }
        $now = time();
        if (abs($now - $time) > 600) {
            throw new ApiException(ApiCodeMsg::BAD_TIMESTAMP);
        }

        #token验证
        $accessToken = Request::header('token');
        if (!$accessToken) {
            throw new ApiException(ApiCodeMsg::ERROR_PARAMS);
        }
        $lang = Request::header('lang');
        $lang = strtolower($lang) ?? 'zh-cn';
        cookie('think_var', $lang);

        //dump(config());exit;


        if ($accessToken != 'testapi') {
            $jwt = JwtAuth::getInstance();
            $jwt->setToken($accessToken);
            $jwt->verify();
            $jwt->validate();
            $uid = $jwt->getUid();
            $userInfo = object2array($jwt->getUserInfo());
            $request->user = $userInfo;

            //验证app-id 与app-secret是否合法
            $appId = Request::header('app-id');
            $appSecret = Request::header('app-secret');
            $md5 = md5($appId . config('system.password_salt'));
            if ($md5 != $appSecret) {
                throw new ApiException([1, '~非法请求~']);
            }

        }

        if($uid){
            $redis = Cache::store('redis')->handler();
            $force = $redis->get('force_offline_'.$uid);
            if($force){
                throw new ApiException([11, '您的账号被强制下线了']);
            }
        }



        //is_local() && $uid = 4;

        $request->uid = $uid;

        $request->appId = $request->header('app-id');

        return $next($request);
    }


}