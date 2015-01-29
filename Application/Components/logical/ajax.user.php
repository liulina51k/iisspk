<?php
/*
*  $id: ajax.article.php 2009-6-17 kelly kong$
*  前台高数据调用提供支持
*/
import('user', 'source');
$ouser = & IUser::getInstance();
$ac = !empty($_GET['ac']) ? $_GET['ac'] : '';

$json = !empty($_GET['json']) ? $_GET['json'] : '';

$param = !empty($_GET['parameter']) ? $_GET['parameter'] : exit('参数错误');

$param = urldecode($param);

$paramarr = explode('_', $param);
if(!call_user_func(array('AjaxUser', $ac))){
    exit(0);
}

class AjaxUser{

    //用户登录状态
    public static function head_login($logininfo = '')
    {
         global $ouser,$paramarr,$json;
         //是否返回json数据
         if(!empty($json)){
             $callback = $_GET['jsoncallback'];
             echo $callback;
         }else{
             echo 'true||##';
         }

         $str = '';
         $login="";
         if($str=$ouser->isLogin()){
             import('subdate');
             $odate = new ISubdate;
             $str .= '<span>'.$odate->getDayAMPM().'好:<a class="redfont">'.$ouser->getUserName().'</a><a href="javascript:user_quit(\'gv_user_login_head\',\'head\');" class="blueonline">退出</a><a class="blueonline" href="'.IConfig::BBSURL.'" target="_blank">进入论坛</a> <a class="blueonline" href="'.IConfig::USERURL.'/home/" target="_blank">进入会员中心</a></span>'.$logininfo;
             $login .='你好,登录成功';
         }else{

             if(!empty($paramarr[1])){
                 $str .= '登录失败！<a class="blue" href="javascript:user_login(\'gv_user_login_head\',\'head\');">重新登录</a>';

             }else{

                $str .= '用户名：<input type="text" name="username" size="10" class="txt"/>
         密码：<input type="password" name="password" size="10"  class="txt"/>
         <input type="submit" name="btnSubmit" onclick="user_loginon(\'gv_user_login_head\',\'head\');" value="登录" class="btn"/> <input type="submit" name="btnSubmit" onclick="window.open(\''.makesiteurl('', 'user', 'reg').'\');" value="注册" class="btn"/>'.$logininfo;
             }
         }

        if(!empty($json)){
             $callback = $_GET['jsoncallback'];
             $str = json_encode($str);
             $login = json_encode($login);
             echo "({data:$str,login:$login})";
         }else{
             echo $str;
         }

    }

    public static function tougao_login($logininfo = ''){
        global $ouser,$paramarr,$json;
         //是否返回json数据
         if(!empty($json)){
             $callback = $_GET['jsoncallback'];
             echo $callback;
         }else{
             echo 'true||##';
         }

         $str = '';
         if( $str = $ouser->isLogin()){
             import('subdate');
             $odate = new ISubdate;
             $str .= $odate->getDayAMPM().'好:<a class="redfont">'.$ouser->getUserName().'</a><br /><a class="blue" href="'.IConfig::USERURL.'/send/"><img src="'.IConfig::BASE.'/images/member_hy.jpg" />我的投稿</a>';
         }

         if(!empty($json)){
             $callback = $_GET['jsoncallback'];
             $str = json_encode($str);
             echo "({data:$str})";
         }else{
             echo $str;
         }
    }
    //pk台登录
    public static function pk_login()
    {
        global $ouser,$paramarr,$json;
        //是否返回json数据
         if ( !empty($json) ) {
             $callback = $_GET['jsoncallback'];
             echo $callback;
         } else {
             echo 'true||##';
         }

         $str = '';
         $logininfo = '';
         if ( $str = $ouser -> checkUser( $paramarr[0], $paramarr[1] ) ) {
             import('subdate');
            $odate = new ISubdate;
            $str .='<p class="log_in">'.$odate -> getDayAMPM().'好:<a class="redcolor">'.$ouser -> getUserName().'</a><a href="javascript:loginout(\'com_login\');" class="blueonline">退出</a><a class="bbs" href="'.IConfig::BBSURL.'">进入论坛</a>
                    <a class="member" href="'.IConfig::USERURL.'/home/">进入会员中心</a></p>';
         } else {
             $logininfo .= '用户名或密码错误';
         }

        if ( !empty( $json ) ) {
             $callback = $_GET['jsoncallback'];
             $str = json_encode($str);
             $logininfo = json_encode( $logininfo );
             echo "({data:$str,logininfo:$logininfo})";
         } else {
             echo $str;
         }
    }

    //用户于中国战略登录处登录

    //实行登录
    public static function head_loginon()
    {
        global $ouser,$paramarr;

        self::head_login($ouser->checkUser($paramarr[0], $paramarr[1]));
    }

    //退出登录
    public static function head_quit()
    {
        global $ouser,$paramarr;
        self::head_login($ouser->quit());
    }

    //战略社区退出登录 2012-3-28
    public static function clubn_quit()
    {
        global $ouser,$paramarr;
        self::clubn_login($ouser->quit());
    }
    
    //退出登录
    public static function people_quit()
    {
        global $ouser,$paramarr;
        self::people_login('',$ouser->quit());
    }
    //用户中国战略频道登录状态
    public static function str_login($logininfo = '')
    {
         global $ouser,$json;

         //是否返回json数据
         if(!empty($json)){
             $callback = $_GET['jsoncallback'];
             echo $callback;
         }else{
             echo 'true||##';
         }

         $str = '';
         if( $str = $ouser->isLogin()){
             $uid = $ouser->getUserid();
             //取得会员的相关信息
             import('member', 'source');
             $omem = IMember::getInstance();
             $groupid = $omem->getMemberInfo($uid, 'groupid');
             $groupid = $omem->getMemberGroup($groupid);
             if($groupid['icon']){
               $group = '<img src="'.IConfig::BBSURL.'/data/attachment/common/'.$groupid['icon'].'" width="40" height="16" border="0" alt="'.$groupid['grouptitle'].'" />';
             }else{
               $group = $groupid['grouptitle'];
             }
             $extcredits1= $omem->getMemberInfo($uid, 'extcredits1');

             //取得会员消息数
             //调用程序通用接口
             include_once I_ROOT.'/uc_client/client.php';
             $pmcountarr = uc_pm_checknew($uid, 2);
             $pmcount = array_sum($pmcountarr);


              $str = '<span class="emil_left"><span>用户名:<a class="red strong">'.$ouser->getUserName().'</a><br>军衔:'.$group.'&nbsp;&nbsp;留言: '.$pmcount.'&nbsp;&nbsp;经验值:'.$extcredits1.'<br><div id="yangjuntianjia"><a class="blue" href="'.IConfig::BBSURL.'"><img src="'.IConfig::BASE.'/images/member_lt.jpg" width="15" height="14">论&nbsp;坛</a><a class="blue" href="'.IConfig::USERURL.'/home/"><img src="'.IConfig::BASE.'/images/member_hy.jpg" width="12" height="14">会员中心</a><a class="blue" href="'.IConfig::USERURL.'/send/"><img src="'.IConfig::BASE.'/images/member_hy.jpg" width="12" height="14">我的投稿</a></div>
          </span></span>';

         }else{


             $str .= '用户名：<input class="text" type="text" name="username" /><input type="image" name="imglogin" src="'.IConfig::BASE.'/images/str/block_bk_login.jpg" onclick="user_loginon(\'gv_user_login_str\', \'str\');return false;" /><Br />密　码：<input class="text" type="password" name="password" /><input type="image" name="imgreg" onclick="window.open(\''.IConfig::BASE.'/user/reg\');return false;" src="'.IConfig::BASE.'/images/str/block_bk_register.jpg" />'.$logininfo;

         }

         if(!empty($json)){
             $callback = $_GET['jsoncallback'];
             $str = json_encode($str);
             echo "({data:$str})";
         }else{
             echo $str;
         }
    }

    //中国战略频道登录
    public static function str_loginon(){
        global $ouser,$paramarr;
        self::str_login($ouser->checkUser($paramarr[0], $paramarr[1]));
    }

    //战略博客登录
    public static function blog_login($logininfo = '')
    {
         global $ouser,$json;

         //是否返回json数据
         if(!empty($json)){
             $callback = $_GET['jsoncallback'];
             echo $callback;
         }else{
             echo 'true||##';
         }

         $str = '';

         if($str = $ouser->isLogin()){
             $uid = $ouser->getUserid();
             //取得会员的相关信息
             import('member', 'source');
             $omem = IMember::getInstance();
             $groupid = $omem->getMemberInfo($uid, 'groupid');
             $groupid = $omem->getMemberGroup($groupid);
             if($groupid['icon']){
               $group = '<img src="'.IConfig::BBSURL.'/data/attachment/common/'.$groupid['icon'].'" width="40" height="16" border="0" alt="'.$groupid['grouptitle'].'" />';
             }else{
               $group = $groupid['grouptitle'];
             }
             $extcredits1= $omem->getMemberInfo($uid, 'extcredits1');

             //取得会员消息数
             //调用程序通用接口
             include_once I_ROOT.'/uc_client/client.php';
             $pmcountarr = uc_pm_checknew($uid, 2);
             $pmcount = array_sum($pmcountarr);

             $str .= '用户名:'.$ouser->getUserName().'<br />军&nbsp;&nbsp;衔:'.$group.'<br />留言: '.$pmcount.';经验值:'.$extcredits1.'<br /><span><a href="'.IConfig::BASE.'/send/add" target="_blank">我要投稿</a></span>';

             import('blog', 'source');
             $oblog = IBlog::getInstance();
             $blogInfo = $oblog->getBlogUidInfo($uid);

             if(!empty($blogInfo)){
                $str .= ' <span><a href="'.IConfig::BLOGURL.'/'.$blogInfo['domain'].'" target="_blank">我的博客</a></span>'.$logininfo;
             }else{
                $str .= '<span><a href="'.IConfig::BASE.'/user/blog" target="_blank">开通博客</a></span>'.$logininfo;
             }

         }else{
             $str .= '用户名<input type="text" name="username" class="text" /><br />密&nbsp;&nbsp;码<input type="password" name="password" class="text" /><br />
             <div class="other"><span style="color:#999"><p class="dl">其他方式登录：</p><a href="###" style="color:#999" class="tt" onclick="out_login( \'qq\' )"><img src="'.IConfig::IMAGEURL.'/images/qq_icon.jpg" class="QQ" title="QQ登录"/><p class="Q">QQ登录</p></a><a href="###" style="color:#999" class="xinlang" onclick="out_login( \'sina\' )"><img src="'.IConfig::IMAGEURL.'/images/sina_icon.jpg" class="sina" title="微博登录"/><p class="s">微博登录</p></a></span></div>
         <div class="emil_right">&nbsp;<label><a href="'.IConfig::BASE.'/send/add" target="_blank">我要投稿</a></label><label><a href="'.IConfig::BASE.'/user/blog" target="_blank">开通博客</a></label><input class="btt" type="image" name="login" src="'.IConfig::BASE.'/images/bloghome_dl.jpg" onclick="user_loginon(\'gv_user_login_blog\', \'blog\');return false;" /></label></div>'.$logininfo;
         }

         if(!empty($json)){
             $callback = $_GET['jsoncallback'];
             $str = json_encode($str);
             echo "({data:$str})";
         }else{
             echo $str;
         }

    }

    //战略博客登录
    public static function blogstate_login($logininfo = '')
    {
         global $ouser,$json;

         //是否返回json数据
         if(!empty($json)){
             $callback = $_GET['jsoncallback'];
             echo $callback;
         }else{
             echo 'true||##';
         }

         $str = '';

         if($str = $ouser->isLogin()){
             $uid = $ouser->getUserid();

             import('blog', 'source');
             $oblog = IBlog::getInstance();
             $blogInfo = $oblog->getBlogUidInfo($uid);

             if(!empty($blogInfo)){
                $str .= ' <a class="blueonline" href="'.IConfig::BLOGURL.'/'.$blogInfo['domain'].'" target="_blank">进入我的博客&gt;&gt;</a>'.$logininfo;
             }else{
                $str .= '<a class="blueonline" href="'.IConfig::BASE.'/user/blog" target="_blank">我要开通博客&gt;&gt;</a>'.$logininfo;
             }

         }else{
             $str .= '<a class="blueonline" href="'.IConfig::BASE.'/user/reg" target="_blank">我要开通博客&gt;&gt;</a>'.$logininfo;
         }

         if(!empty($json)){
             $str = json_encode($str);
             echo "({data:$str})";
         }else{
             echo $str;
         }
    }

    //战略博客登录
    public static function blog_loginon(){
        global $ouser,$paramarr;

        self::blog_login($ouser->checkUser($paramarr[0], $paramarr[1]));
    }


    //战略社区登录
    public static function club_login($logininfo = '')
    {
         global $ouser,$json;

         //是否返回json数据
         if(!empty($json)){
             $callback = $_GET['jsoncallback'];
             echo $callback;
         }else{
             echo 'true||##';
         }

         $str = '';
         if($str = $ouser->isLogin()){
             $uid = $ouser->getUserid();
             //取得会员的相关信息
             import('member', 'source');
             $omem = IMember::getInstance();
             $groupid = $omem->getMemberInfo($uid, 'groupid');
             $groupid = $omem->getMemberGroup($groupid);
             if($groupid['icon']){
               $group = '<img style="vertical-align:middle" src="'.IConfig::BBSURL.'/data/attachment/common/'.$groupid['icon'].'" width="40" height="16" border="0" alt="'.$groupid['grouptitle'].'" />';
             }else{
               $group = $groupid['grouptitle'];
             }
             $extcredits1= $omem->getMemberInfo($uid, 'extcredits1');

             //头像图片
             $avatarpic = avatar($uid, 'middle');

             $str .= '<div class="fleft"><img src="'.$avatarpic.'" width="72" height="72" /></div><div class="fleft" style="margin-left:10px;display:inline;line-height:16px;">用户名:'.$ouser->getUserName().'<br />军&nbsp;&nbsp;衔:'.$group.'<br />经验值:'.$extcredits1.'<br /><a class="blue" href="'.IConfig::BBSURL.'"><img src="'.IConfig::BASE.'/images/member_lt.jpg" />论坛</a> <a class="blue" href="'.IConfig::USERURL.'/home/"><img src="'.IConfig::BASE.'/images/member_hy.jpg" />会员中心</a></div><div class="clear"></div>';

         }else{

             $str .= '用户名：<input class="input_kd" name="username" type="text" /><input name="" type="image" src="'.IConfig::BASE.'/images/note.jpg" onclick="window.open(\''.IConfig::BASE.'/user/reg\');return false;" /><br />密　码：<input class="input_kd" type="password" name="password" /><input name="" type="image" src="'.IConfig::BASE.'/images/login.jpg" onclick="user_loginon(\'gv_user_login_club\', \'club\');return false;" /><div class="club_password"><input name="" type="checkbox" value="" />记录账号　　<a class="blueonline" href="{$site}/user/findpass" target="_blank">忘记密码</a></div>'.$logininfo;
         }

         if(!empty($json)){
             $str = json_encode($str);
             echo "({data:$str})";
         }else{
             echo $str;
         }

    }

    //战略社区登录
    public static function club_loginon(){
        global $ouser,$paramarr;
        self::club_login($ouser->checkUser($paramarr[0], $paramarr[1]));
    }

    //战略社区登录2011-7-6
    public static function clubn_login($logininfo = '')
    {
         global $ouser,$json;

         //是否返回json数据
         if(!empty($json)){
             $callback = $_GET['jsoncallback'];
             echo $callback;
         }else{
             echo 'true||##';
         }

         $str = '';
         if($str = $ouser->isLogin()){
             $uid = $ouser->getUserid();
             //取得会员的相关信息
             import('member', 'source');
             $omem = IMember::getInstance();
             $groupid = $omem->getMemberInfo($uid, 'groupid');
             $groupid = $omem->getMemberGroup($groupid);
             if($groupid['icon']){
               $group = '<img style="vertical-align:middle" src="'.IConfig::BBSURL.'/data/attachment/common/'.$groupid['icon'].'" width="40" height="16" border="0" alt="'.$groupid['grouptitle'].'" />';
             }else{
               $group = $groupid['grouptitle'];
             }
             $extcredits1= $omem->getMemberInfo($uid, 'extcredits1');

             //头像图片
             $avatarpic = avatar($uid, 'middle');

             $str .= '<div style="margin-left:10px; position:relative; width:95%;text-align:left;"><div class="fleft"><img src="'.$avatarpic.'" width="72" height="72" /></div><div class="fleft" style="margin-left:10px;display:inline;line-height:16px;">用户名:'.$ouser->getUserName().'<br />军&nbsp;&nbsp;衔:'.$group.'<br />经验值:'.$extcredits1.'<br /><a class="blue" href="'.IConfig::BBSURL.'">论坛</a>&nbsp;&nbsp;&nbsp;<a class="blue" href="'.IConfig::USERURL.'/home/">会员中心</a></div><div class="clear"></div><a href="javascript:user_quit(\'gv_user_login_clubn\',\'clubn\');" class="log_out_btn">退出</a></div>';

         }else{
             $str .= '用户名：<input class="text" type="text" name="username" /><input type="image" onclick="user_loginon(\'gv_user_login_clubn\', \'clubn\');return false;" src="'.IConfig::BASE.'/images/club/block_bk_login.jpg" /><Br />密　码：<input class="text" type="password" name="password" /><input type="image" onclick="window.open(\''.IConfig::BASE.'/user/reg\');return false;" src="'.IConfig::BASE.'/images/club/block_bk_register.jpg" /><Br /><label><input type="checkbox" value="checkbox" />记录账号</label><a class="blueonline" href="'.IConfig::BASE.'/user/findpass" target="_blank">忘记密码</a>';
         }

         if(!empty($json)){
             $str = json_encode($str);
             echo "({data:$str})";
         }else{
             echo $str;
         }

    }

    //战略社区登录2011-7-6
    public static function clubn_loginon(){
        global $ouser,$paramarr;
        self::clubn_login($ouser->checkUser($paramarr[0], $paramarr[1]));
    }

    //正文评论处 通过外部登录   by dean
    public static function comment_ologin($att = '', $logininfo=''){
        global $ouser,$json;

         //是否返回json数据
         if(!empty($json)){
             $callback = $_GET['jsoncallback'];
             echo $callback;
         }else{
             echo 'true||##';
         }
         $str = '';

         if($str = $ouser->isLogin()){
             import('subdate');
             $odate = new ISubdate;
             $str .= '<span>'.$odate->getDayAMPM().'好:<a class="redfont">'.$ouser->getUserName().'</a> <a class="blueonline" href="'.IConfig::BBSURL.'/space.php?uid='.$ouser->getUserid().'">进入论坛</a> <a class="blueonline" href="'.IConfig::USERURL.'/home/">进入会员中心</a></span><input name="anonymous" type="checkbox" value="1" />匿名'.$logininfo;

         }else{

             $str .= '用户名<input class="text" type="text" name="username" />密码<input class="text" type="password" name="password" /><input type="submit" name="Submit" value="登录" onclick="user_loginon(\'gv_user_ologin_comment'.$att.'\', \'comment\');" /><input type="submit" onclick="window.open(\''.IConfig::BASE.'/user/reg\');" value="注册" /><span style="color:#999">其他方式登录：<a href="###" style="color:#999" onclick="out_login( \'sina\' )"><img src="'.IConfig::IMAGEURL.'/images/sina_icon.jpg" class="sina" title="微博登录"></a><a href="###" style="color:#999" onclick="out_login( \'qq\' )"><img src="'.IConfig::IMAGEURL.'/images/qq_icon.jpg" class="QQ" title="QQ登录"></a></span><script>$("input[name=\'anonymous\']").attr("checked", "checked");</script>';
         }

         if(!empty($json)){
             $str = json_encode($str);
             echo "({data:$str})";
         }else{
             echo $str;
         }

    }


    //正文评论处登录
    public static function comment_login($att = '', $logininfo=''){
        global $ouser,$json;

         //是否返回json数据
         if(!empty($json)){
             $callback = $_GET['jsoncallback'];
             echo $callback;
         }else{
             echo 'true||##';
         }
         $str = '';

         if($str = $ouser->isLogin()){
             import('subdate');
             $odate = new ISubdate;
             //2012-5-8修改
             //$str .= '<span>'.$odate->getDayAMPM().'好:<a class="redfont">'.$ouser->getUserName().'</a> <a class="blueonline" href="'.IConfig::BBSURL.'/space.php?uid='.$ouser->getUserid().'">进入论坛</a> <a class="blueonline" href="'.IConfig::USERURL.'/home/">进入会员中心</a></span>'.$logininfo;
             $str .= '<span>'.$odate->getDayAMPM().'好:<a class="redfont">'.$ouser->getUserName().'</a> <a href="javascript:user_quit(\'gv_user_login_comment\',\'comment\');" class="blueonline">退出</a>  <a class="blueonline" href="'.IConfig::BBSURL.'/space.php?uid='.$ouser->getUserid().'">进入论坛</a> <a class="blueonline" href="'.IConfig::USERURL.'/home/">进入会员中心</a></span>'.$logininfo;

         }else{

            // onclick="user_loginon('gv_user_login_comment', 'comment');"
            // onclick="user_loginon(\'gv_user_login_comment'.$att.'\',\'comment\');return false;"
             //2012-5-8修改
             //$str .= '用户名<input class="text" type="text" name="username" />密码<input class="text" type="password" name="password" /><input type="submit" name="Submit" value="登录" onclick="user_loginon(\'gv_user_login_comment'.$att.'\',\'comment\');"  /><input type="submit" onclick="window.open(\''.IConfig::BASE.'/user/reg\');" value="注册" /><script>$("input[name=\'anonymous\']").attr("checked", "checked");</script>';
            $str .= '用户名<input class="text" type="text" name="username" />密码<input class="text" type="password" name="password" /><input type="submit" name="Submit" value="登录" onclick="user_loginon(\'gv_user_login_comment'.$att.'\', \'comment\');" class="btn_50" /><input type="submit" value="注册" onclick="window.open(\'http://www.chinaiiss.com/user/reg\');" class="btn_50"><script>$("input[name=\'anonymous\']").attr("checked", "checked");</script>';
         }

         if(!empty($json)){
             $str = json_encode($str);
             echo "({data:$str})";
         }else{
             echo $str;
         }

    }


    //致敬历史正文评论处登录
    public static function people_login($att = '', $logininfo=''){
        global $ouser,$json;

         //是否返回json数据
         if(!empty($json)){
             $callback = $_GET['jsoncallback'];
             echo $callback;
         }else{
             echo 'true||##';
         }
         $str = '';

         if($str = $ouser->isLogin()){
             import('subdate');
             $odate = new ISubdate;
             $str .= '<p class="one">'.$odate->getDayAMPM().'好:'.$ouser->getUserName().
             '<a href="javascript:user_quit(\'gv_user_login_people\',\'people\');">退出</a></p>'.$logininfo;

         }else{

            $str .= '<p>用户名</p><input name="username" type="text" />
           <p>密&nbsp;&nbsp;码</p><input name="password" type="password" />
           <input type="button" value=" " class="button1"  onclick="user_loginon(\'gv_user_login_people'.$att.'\', \'people\'); return false;"/>
           <input type="button" value=" " class="button2" onclick="window.open(\''.IConfig::BASE.'/user/reg\');
            return false;"/>';
         }

         if(!empty($json)){
             $str = json_encode($str);
             echo "({data:$str})";
         }else{
             echo $str;
         }

    }

    //时政专题评论登录
    public static function commentnews_login($att = '', $logininfo=''){
        global $ouser,$json;

         //是否返回json数据
         if(!empty($json)){
             $callback = $_GET['jsoncallback'];
             echo $callback;
         }else{
             echo 'true||##';
         }
         $str = '';

         if($str = $ouser->isLogin()){
             import('subdate');
             $odate = new ISubdate;
             $str .= '<span>'.$odate->getDayAMPM().'好:<a class="redfont">'.$ouser->getUserName().'</a> <a class="blueonline" href="'.IConfig::BBSURL.'/space.php?uid='.$ouser->getUserid().'">进入论坛</a> <a class="blueonline" href="'.IConfig::USERURL.'/home/">会员中心</a></span>'.$logininfo;

         }else{
             $str .= '用户名：<input class="text" type="text" name="username" /><input type="image" name="loginbtn" src="'.IConfig::BASE.'/images/spec/block_bk_login.jpg" onclick="user_loginon(\'gv_user_login_commentnews'.$att.'\', \'commentnews\');return false;" /><Br />密　码：<input class="text" type="password" name="password" /><input type="image" src="'.IConfig::BASE.'/images/spec/block_bk_register.jpg" onclick="window.open(\''.IConfig::BASE.'/user/reg\');return false;" /><span class="d"><p>其他方式登录:</p><a href="###" title="微博登录" onclick="out_login( \'sina\' )"><img src="'.IConfig::BASE.'/images/sina_icon.jpg" class="sina"/><p>微博登录</p></a><a href="###"  title="QQ登录" onclick="out_login( \'qq\' )"><img src="'.IConfig::BASE.'/images/qq_icon.jpg" class="QQ"/><p>QQ登录</p></a></span>';
         }

         if(!empty($json)){
             $str = json_encode($str);
             echo "({data:$str})";
         }else{
             echo $str;
         }

    }

    //实行登录
    public static function commentnews_loginon()
    {
        global $ouser,$paramarr;

        self::commentnews_login($ouser->checkUser($paramarr[0], $paramarr[1]));
    }

    //评论第二处会员登录
    public static function comment_up_login(){
       self::comment_login('_up');
    }

    //正文评论处登录
    public static function comment_loginon(){
        global $ouser,$paramarr;
        self::comment_login('',  $ouser->checkUser($paramarr[0], $paramarr[1]));
    }

    //致敬历史评论处登录
    public static function people_loginon(){
        global $ouser,$paramarr;
        self::people_login('',  $ouser->checkUser($paramarr[0], $paramarr[1]));
    }


    //查证用户名是否存在
    public static function verifyname()
    {
        //调用程序通用接口
        if(!@include_once I_ROOT.'/uc_client/client.php'){
            echo '系统错误';
        }
        if(!is_numeric($_GET['username'])){
           echo uc_user_checkname($_GET['username']);
        }else{
           echo '2';
        }

    }

    //2012-8-2添加邮箱是否存在验证
    public static function verifyemail()
    {
        //调用程序通用接口
        if(!@include_once I_ROOT.'/uc_client/client.php'){
            echo '系统错误';
        }
        if(!is_numeric($_GET['email'])){
           echo uc_user_checkemail($_GET['email']);
        }else{
           echo '2';
        }

    }
    
    //添加会员好友
    public static function addfriend()
    {
        global $ouser,$json;

        $alertstr = 'true||##apalert("提示信息", "{$message}", $("#friend'.$_GET['dom'].'").offset().top, $("#friend'.$_GET['dom'].'").offset().left+100, 240)';

        if($ouser->isLogin()){
            //调用程序通用接口
            include_once I_ROOT.'/uc_client/client.php';

            //检查好友是否已经存在
            $friendlist = uc_friend_ls($ouser->getUserid());
            foreach($friendlist as $value){
                 if($value['friendid'] == $_GET['dom']){
                        echo str_replace('{$message}', '好友已经存在！', $alertstr);
                        exit;
                 }
            }

            if(uc_friend_add($ouser->getUserid(), $_GET['dom'])){
               $message = '成功';
            }else{
                $message = '失败';
            }

            echo str_replace('{$message}', '好友添加'. $message .'！', $alertstr);

        }else{
            echo str_replace('{$message}', '你还没有登录，不能进行此操作！', $alertstr);
        }
    }


    //删除好友
    public static function deletefriend()
    {
         global $ouser,$paramarr,$json;

         if($ouser->isLogin()){
             //调用程序通用接口
             include_once I_ROOT.'/uc_client/client.php';

             $uid = $ouser->getUserid();
             $friendid = intval($_GET['dom']);
             uc_friend_delete($uid, array($friendid));

             if(!empty($json)){
                 $callback = $_GET['jsoncallback'];
                 echo $callback;
                 $str = json_encode("true||##refreshpage()");
                 echo "({data:$str})";
             }else{
                 echo 'true||##refreshpage()';
             }

         }
    }

    //发送会员信息
    public static function sendpm()
    {
         global $ouser,$paramarr,$json;
        if(!empty($json)){
             $callback = $_GET['jsoncallback'];
             echo $callback;
         }else{
             echo 'true||##';
         }
         $str = '';
         $alertstr = 'true||##apalert("提示信息", "{$message}", $("#pm'.$_GET['dom'].'").offset().top+30, $("#pm'.$_GET['dom'].'").offset().left, 360)';

         if($ouser->isLogin()){

             if($paramarr[0] == $_GET['dom']){

                $commhtml = "<div style='text-align:left;'>姓名:<input name='username' value='".$ouser->getMember($_GET['dom'], 'username')."' type='text' size='32'/><br /><textarea name='pmcontent' cols='40' rows='5'></textarea></div><input name=submit type=button onclick=user_pm('{$_GET['dom']}','send'); class=btn value=提交>";

                $str = str_replace('{$message}', $commhtml, $alertstr);

             }else{
                //调用程序通用接口
                include_once I_ROOT.'/uc_client/client.php';
                if(uc_pm_send($ouser->getUserid(), $_GET['dom'], '', $paramarr[0])) {
                    $str = str_replace('{$message}', "短消息发送成功", $alertstr);
                }else{
                    $str = 'true||##alert("提交失败");';
                }

             }

         }else{
             $str = str_replace('{$message}', '你还没有登录，不能进行此操作！', $alertstr);
        }

        if(!empty($json)){
            $str = json_encode($str);
            echo "({data:$str})";
        }else{
            echo $str;
        }
    }

    //添加好友备注
    public static function comment()
    {
         global $ouser,$paramarr;

         if($ouser->isLogin()){
             //调用程序通用接口
             include_once I_ROOT.'/uc_client/client.php';

             $uid = $ouser->getUserid();

             if($comment = getstr(shtmlspecialchars($paramarr[0]), 255)) {
                $friendid = intval($_GET['dom']);
                uc_friend_delete($uid, array($friendid));
                uc_friend_add($uid, $friendid, $comment);
            }
         }

    }

    //验证博客域名是否存在
    public static function blogdomain()
    {
        if(is_numeric($_GET['domain'])){
           echo "3";
        }
        if(!empty($_GET['domain'])){
            $db = & IFactory::getDB();
            $domain = $_GET['domain'];
            $sql = "select uid from iissblog_user where domain='$domain'";
            $query = $db->query($sql);
            if($value = $db->fetch_array($query)){
               echo "2";
               exit;
            }
        }

        echo "1";
    }

    //验证博客会员昵称是否存在
    public static function blogname()
    {
        global $json;
        //是否返回json数据
        if(!empty($json)){
            $callback = $_GET['jsoncallback'];
            echo $callback;
        }else{
            echo 'true||##';
        }

        if(!empty($_GET['name'])){
            $db = & IFactory::getDB();
            $name = $_GET['name'];
            $sql = "select uid from iissblog_user where name='$name'";
            $query = $db->query($sql);
            if($value = $db->fetch_array($query)){
                echo "({data:2})";
                exit;
            }
        }
        echo "({data:1})";
    }

    //会员中心 设置信息为已读状态
    public static function setread(){
        global $json,$ouser,$paramarr;

        //是否返回json数据
        if(!empty($json)){
         $callback = $_GET['jsoncallback'];
         echo $callback;
        }else{
         echo 'true||##';
        }
        $db = & IFactory::getDB();
        $sql="UPDATE `iissblog_comment` SET `isread` = 1 WHERE `idtype`='".$paramarr[0]."' and uid=".$paramarr[1]." ";
        $query = $db->query($sql);
        echo "({data:'1'})";
    }

    //通用评论页 登录  2012-1-29
    public static function comm_login($att = '', $logininfo=''){
        global $ouser,$json,$paramarr;

         //是否返回json数据
         if(!empty($json)){
             $callback = $_GET['jsoncallback'];
             echo $callback;
         }else{
             echo 'true||##';
         }
         $str = '';

         if($ouser->isLogin()){
             import('subdate');
             $odate = new ISubdate;
             if($_GET['dom'] == 'gv_user_login'){
                $str .= '<span style="float:left">'.$ouser->getUserName().'</span>
                    <a class="blueonline" href="javascript:quit_quit();">退出</a>'; 
             }else{
             	//2013-08-22更改评论框样式
				$str .='<div class="plk_foot_l_02">'.$odate->getDayAMPM().'好：<span class="plk_foot_l_name">'.$ouser->getUserName().'</span><a href="'.IConfig::USERURL.'/home/">个人中心</a>|<a href="javascript:quit_quit();">退出</a></div>';
                $str .='<div class="plk_foot_r"><span class="plk_foot_checkbox"><input name="anonymous" type="checkbox" value="1" /></span><span class="plk_foot_niming">匿名</span><a class="plk_button" id="publish" href="javascript:void(0);"  onclick="newcomments_submit(\'comm_downsumbit\',\'comment\');">发布</a></div>';
                //$str .='<div class="plk_foot_r"><span class="plk_foot_checkbox"><input name="anonymous" type="checkbox" value="1" /></span><span class="plk_foot_niming">匿名</span><input type="button"  class="plk_button" value="发布" id="publish" onclick="newcomments_submit(\'comm_downsumbit\',\'comment\');"/></div>';
                //$str.='<p class="good"><span class="sw">'.$odate->getDayAMPM().'好：</span><span class="name2">'.$ouser->getUserName().'</span><!--2012年7月4日添加退出--><span class="tc"><a href="javascript:quit_quit();">退出</a></span><!--2012年7月4日添加退出结束--><a href="'.IConfig::BBSURL.'/space.php?uid='.$ouser->getUserid().'" class="into">进入论坛</a><a href="'.IConfig::USERURL.'/home/" class="vip">进入会员中心</a></p>'.$logininfo;   
             }

         }else{
             if(isset($paramarr[1])){
                 $str .= '<script>alert("用户名或密码错误!")</script>';
             }
             $username = '';
             if(!intval($paramarr[0])){
                 $username = $paramarr[0];
             }
             if($_GET['dom'] == 'gv_user_login_comm'){
             	//2013-08-22更改评论框样式
             	$str .= '<div id="'.$_GET['dom'].'" class="plk_foot_l_01"><input name="username" id="username" type="text" class="plk_foot_l_input" value="用户名" onclick="	if ($(this).val() == \'用户名\') {$(this).val(\'\');}" onblur="if($(this).val() == \'\') {$(this).val(\'用户名\');}"/><input name="password" id="password" type="password" class="plk_foot_l_input" value="" style="display:none;" onblur="if($(this).val()== this.defaultValue){$(\'#showpass\').show();$(this).hide();}" /><input type="text" value="密码" id="showpass"  class="plk_foot_l_input" onfocus="$(this).hide();$(\'#password\').show().focus();"/> <a class="SINA_login" href="javascript:void(0);" onclick="out_login(\'sina\');">&nbsp;</a><a class="QQ_login" href="javascript:void(0);" onclick="out_login(\'qq\');">&nbsp;</a><a href="'.IConfig::BASE.'/user/reg" class="plk_foot_zc">注册</a></div>';
             	$str .='<div class="plk_foot_r"><span class="plk_foot_checkbox"><input name="anonymous" type="checkbox" value="1" /></span><span class="plk_foot_niming">匿名</span><a class="plk_button" id="loginpublish" href="javascript:void(0);"  onclick="newcomments_submit(\'comm_downsumbit\',\'comment\');">登录并发布</a></div>';
             	//$str .= '<div id="'.$_GET['dom'].'">用户名<input buttonid="button" class="text" type="text" name="username" value="'.$username.'" />密码<input class="text" onfocus="enterSubmit($(this));" buttonid="button" type="password" name="password" /><input class="n_border" type="button" onclick="user_login_pro(\''.$_GET['dom'].$att.'\', \'comm\' ,\'location.reload()\');" id="button" /><input type="button" onclick="window.open(\''.IConfig::BASE.'/user/reg\');" id="button2"/><a class="SINA_login" href="###" onclick="out_login(\'sina\');">&nbsp;</a><a class="QQ_login" href="###" onclick="out_login(\'qq\');">&nbsp;</a><script>$("input[name=\'anonymous\']").attr("checked", "checked");</script></div>';
             }else{
                $str .= '<div id="'.$_GET['dom'].'">用户名 <input buttonid="button" class="text" type="text" name="username" value="'.$username.'" /> 密码 <input class="text" onfocus="enterSubmit($(this));" buttonid="button" type="password" name="password" /> <input class="n_border" type="button" onclick="user_loginon(\''.$_GET['dom'].$att.'\', \'comm\' ,\'location.reload()\');" id="button" /><input type="button" onclick="window.open(\''.IConfig::BASE.'/user/reg\');" id="button2"/> <a class="SINA_login" href="###" onclick="out_login(\'sina\');">&nbsp;</a><a class="QQ_login" href="###" onclick="out_login(\'qq\');">&nbsp;</a> <script>$("input[name=\'anonymous\']").attr("checked", "checked");</script></div>';
             }

         }

         if(!empty($json)){
             $str = json_encode($str);
             echo "({data:$str})";
         }else{
             echo $str;
         }

    }
    //通用评论页 登录  2012-1-29
    public static function comm_loginon(){
        global $ouser,$paramarr;
        self::comm_login('',  $ouser->checkUser($paramarr[0], $paramarr[1]));
    }

    /*
    * 2012-4-1 增加用户转发文章/图片增加积分
    */
    public static function user_share(){
        global $ouser;
        if($ouser->isLogin()){
            update_usercredit($ouser->getUserid(), $ouser->getUserName(), 'share');
        }
        $str = json_encode('true||##');
        echo "({data:$str})";
    }
    
        
    /**
    *  公用用户登录函数
    * 返回值：json  array('result'=>0|1, 'userid'=>111, 'username'=>'xxx', 'rsynclogin'=>'xxxx')
    */
    public static function user_login()
    {
        global $ouser, $param;
        $rsynclogin = '';
        //是否登录
        if($rsynclogin = $ouser->isLogin()){
            $ret['result'] = '0';
            $ret['userid'] = $ouser->getUserid();
            $ret['username'] = $ouser->getUserName();
            $ret['rsynclogin'] = $rsynclogin;
        }else{
            
            //未登录
            if($param=='show'){
                $ret['result'] = '1';
                $ret['rsynclogin'] = '';
            }else{
                //登录
                $arrparam = explode('_', $param);
                if($rsynclogin = $ouser->checkUser($arrparam[0], $arrparam[1])){
                    $ret['result'] = '0';
                    $ret['userid'] = $ouser->getUserid();
                    $ret['username'] = $ouser->getUserName();
                    $ret['rsynclogin'] = $rsynclogin;
                }else{
                    $ret['result'] = '1';
                    $ret['rsynclogin'] = '';
                }
            }
        }

        $str = json_encode($ret);
        echo $_GET['jsoncallback']."({data:$str})";
    }

    public static function user_logout()
    {
        global $ouser;
        $ret = array();
        $ret['rsynclogin'] = $ouser->quit();
        $ret['result'] = !empty($ret['rsynclogin']) ? '0' : '1';
        
        $str = json_encode($ret);
        echo $_GET['jsoncallback']."({data:$str})";
    }

    public static function comment_quit()
    {
        global $ouser,$paramarr;
        self::comment_login('',$ouser->quit());
    }
    
    //战略观察处评论登录 2012-6-25
    public static function observenews_login($att = '', $logininfo=''){
        global $ouser,$json;

         //是否返回json数据
         if(!empty($json)){
             $callback = $_GET['jsoncallback'];
             echo $callback;
         }else{
             echo 'true||##';
         }
         $str = '';

         if($ouser->isLogin()){
             import('subdate');
             $odate = new ISubdate;
             //战略观察改版2012-6-11注释
             $str .= '<span class="other">'.$odate->getDayAMPM().'好:</span><span class="pers">'.$ouser->getUserName().'</span><a href="javascript:quit_quit();" class="login_out">退出</a>'.$logininfo; 
         }else{
             //战略观察改版2012-6-11注释
             $str .= '<label>用户名:&nbsp;</label><input class="txt_com" onfocus="enterSubmit($(this))" buttonid="observenews_login" type="text" name="username" /><label>&nbsp;密码:&nbsp;</label><input type="hidden" name="anchor" value="commentanchor" /><input class="txt_com"  onfocus="enterSubmit($(this))" buttonid="observenews_login"  type="password" name="password" /><input class="login_btn" id="observenews_login" value="&nbsp;" type="button" name="loginbtn" onclick="user_login_pro(\'gv_user_login_observenews'.$att.'\', \'comm\' ,\'location.reload()\');return false;" /><input type="button" class="register" onclick="window.open(\''.IConfig::BASE.'/user/reg\');return false;" value="&nbsp;" /> <span class="other">其它方式登录：</span><a onclick="out_login(\'sina\')" href="###" class="SINA_login">微博登录</a><a onclick="out_login(\'qq\')" href="###" class="QQ_login">QQ登录</a>';
         }

         if(!empty($json)){
             $str = json_encode($str);
             echo "({data:$str})";
         }else{
             echo $str;
         }

    }
    
    //战略观察登录 2012-6-25
    public static function observenews_loginon()
    {
        global $ouser,$paramarr;

        self::observenews_login($ouser->checkUser($paramarr[0], $paramarr[1]));        
    }
    
    //一周一世界评论登录2012-7-10
    public static function weekworld_login($att = '', $logininfo='')
    {
        global $ouser,$json;
        
        //是否返回json数据
        if(!empty($json)){
            $callback = $_GET['jsoncallback'];
            echo $callback;
        }else{
            echo 'true||##';
        }
        $str = '';
        
        if($ouser->isLogin()){
             import('subdate');
             $odate = new ISubdate;
             //登录后样式
             $str .= '<label>欢迎您，</label><a href="#" class="register">'.$ouser->getUserName().'</a><a href="javascript:user_quit(\'gv_user_login_weekworld\',\'weekworld\');" class="register">退出</a>'.$logininfo; 
         }else{
             //登录前样式
             $str .= '<label>用户名:&nbsp;</label><input class="txt_com" type="text" name="username" /><label>&nbsp;密码:&nbsp;</label><input class="txt_com" type="password" name="password" /><input class="login_btn" value="登录" type="submit" name="loginbtn" onclick="user_loginon(\'gv_user_login_weekworld'.$att.'\', \'weekworld\');return false;" /><input type="button" class="login_btn" onclick="window.open(\''.IConfig::BASE.'/user/reg\');return false;" value="注册" /> <span class="other">其它方式登录：</span><a onclick="out_login(\'sina\')" href="###" class="SINA_login">微博登录</a><a onclick="out_login(\'qq\')" href="###" class="QQ_login">QQ登录</a>';
         }

         if(!empty($json)){
             $str = json_encode($str);
             echo "({data:$str})";
         }else{
             echo $str;
         }
        
    }
    
    //一周一世界登录2012-7-10
    public static function weekworld_loginon()
    {
        global $ouser,$paramarr;

        self::weekworld_login($ouser->checkUser($paramarr[0], $paramarr[1]));        
    }
    
    //一周一世界注销2012-7-10
    public static function weekworld_quit()
    {
       global $ouser,$paramarr;
       self::weekworld_login('',$ouser->quit());
    }
    //12.7.24  新登录页面登录
    public static function iiss_login(){
        global $ouser,$paramarr,$json;
        
        if(!empty($json)){
             $callback = $_GET['jsoncallback'];
             echo $callback;
         }
        $ouser->checkUser($paramarr[0], $paramarr[1]);
        
        $str = 0;
         if($ouser->isLogin()){
            $str = 1;
         }

         if(!empty($json)){
             $callback = $_GET['jsoncallback'];
             $str = json_encode($str);
             echo "({data:$str})";
         }else{
             echo $str;
         }
    }
    //2012-9-25 增加真实用户唯一验证
    public static function verify_turename(){
        $db = & IFactory::getDB();
        $truename = isset($_GET['truename']) ? $_GET['truename'] : '';
        
        $data = array();
        if(!empty($truename)){
            $data = $db->fetch_array($db->query("select truename from iiss_member where truename='$truename'"));
        }
        if(!empty($data)){
            echo "1";
        }else{
            echo "0";
        }
    }
    //2012-9-25 增加身份证唯一验证
    public static function verify_usercard(){
        $db = & IFactory::getDB();
        $usercard = isset($_GET['usercard']) ? $_GET['usercard'] : '';
        $data = array();
        if(!empty($usercard)){
            $data = $db->fetch_array($db->query("select usercard from iiss_member where usercard='$usercard'"));
        }
        if(!empty($data)){
            echo "1";
        }else{
            echo "0";
        }
    }
    // 高清图正文页 评论登录状态
    public static function hdpic_loginon()
    {
        global $ouser,$paramarr;

        self::hdpic_login($ouser->checkUser($paramarr[0], $paramarr[1]));        
    }
    // 高清图正文页 评论登录
    public static function hdpic_login($att = '', $logininfo=''){
        global $ouser,$json,$paramarr;
         //是否返回json数据
         if(!empty($json)){
             $callback = $_GET['jsoncallback'];
             echo $callback;
         }else{
             echo 'true||##';
         }
         $str = '';

         if($ouser->isLogin()){
             import('subdate');
             $odate = new ISubdate;
			 $str .= '<div class="dengleft noline"><form>'.$odate->getDayAMPM().'好：'.$ouser->getUserName().'<a target="_blank" href="'.IConfig::USERURL.'home/">个人中心</a><a>|</a><a href="javascript:quit_quit();">退出</a></form></div>'.$logininfo;
         }else{
			 $username = "";
			 $exthtml = "";
			 if(!empty($paramarr[0]) && !is_numeric($paramarr[0]))
			 {
			 	$username = $paramarr[0];
				$exthtml = '<script>alert("用户名或密码错误！");$("#username").focus();</script>';
			 }
             $str .= '<div class="dengleft"><form onsubmit="return false;">用户名:&nbsp;<input id="username" onfocus="enterSubmit($(this))" value="'.$username.'" buttonid="hdpic_login" name="username" type="text" class="dengleftte" />密码:&nbsp;<input onfocus="enterSubmit($(this))" buttonid="hdpic_login" name="password" type="password" class="dengleftte" /><input type="hidden" name="anchor" value="commentanchor" /><input type="button" id="hdpic_login" onclick="hd_login(\'gv_user_login_hdpic\',\'hdpic_login\');" class="dengleftbu" value="登录"  /><a href="'.IConfig::BASE.'/user/reg/">注册</a></form></div><div class="dengright"><p>其他方式登录:<img src="'.IConfig::BASE.'/images/pic/xin_03.jpg" /><a href="javascript:out_login(\'sina\')">微博登录</a><img src="'.IConfig::BASE.'/images/pic/teng_03.jpg" /><a href="javascript:out_login(\'qq\')">腾讯登录</a></p></div>'.$exthtml;
         }

         if(!empty($json)){
             $str = json_encode($str);
             echo "({data:$str})";
         }else{
             echo $str;
         }

    }
	//2012博主评选登录
    public static function blogger2012_login($att = '', $logininfo=''){
        global $ouser,$json;

         //是否返回json数据
         if(!empty($json)){
             $callback = $_GET['jsoncallback'];
             echo $callback;
         }else{
             echo 'true||##';
         }
         $str = '';

         if($str = $ouser->isLogin()){
             import('subdate');
             $odate = new ISubdate;
             $str .= '<span>'.$odate->getDayAMPM().'好:<a class="redfont">'.$ouser->getUserName().'</a> <a href="javascript:quit_quit();" class="blueonline">退出</a>  <a class="blueonline" href="'.IConfig::BBSURL.'/space.php?uid='.$ouser->getUserid().'">进入论坛</a> <a class="blueonline" href="'.IConfig::USERURL.'/home/">进入会员中心</a></span>'.$logininfo;

         }else{
            $str .= '<input type="hidden" name="anchor" value="usercomment">用户名<input class="text" type="text" name="username" />密码<input class="text" onfocus="enterSubmit($(this));" type="password" name="password" buttonid="loginbtn" /><input id="loginbtn" type="submit" name="Submit" value="" onclick="user_login_pro(\''.$_GET['dom'].'\',\'newreply\');" class="btn_50" /><input type="submit" value="" onclick="window.open(\'http://www.chinaiiss.com/user/reg\');" class="btn_50"><script>$("input[name=\'anonymous\']").attr("checked", "checked");</script>';
         }

         if(!empty($json)){
             $str = json_encode($str);
             echo "({data:$str})";
         }else{
             echo $str;
         }

    }
    
    //正文页登录 2013-08-27
    public static function comm_view_login($att = '', $logininfo=''){
    	global $ouser,$json,$paramarr;
    
    	//是否返回json数据
    	if(!empty($json)){
    		$callback = $_GET['jsoncallback'];
    		echo $callback;
    	}else{
    		echo 'true||##';
    	}
    	$str = '';
    
    	if($ouser->isLogin()){
    		import('subdate');
    		$odate = new ISubdate;
    		if($_GET['dom'] == 'gv_user_login'){
    			$str .= '<span style="float:left">'.$ouser->getUserName().'</span>
                    <a class="blueonline" href="javascript:quit_quit();">退出</a>';
    		}else{
    			//2013-08-22更改评论框样式
    			$str .='<div class="plk_foot_l_02">'.$odate->getDayAMPM().'好：<span class="plk_foot_l_name">'.$ouser->getUserName().'</span><a href="'.IConfig::USERURL.'/home/">个人中心</a>|<a href="javascript:quit_quit();">退出</a></div>';
    			$str .='<div class="plk_foot_r"><span class="plk_foot_checkbox"><input name="anonymous" type="checkbox" value="1" /></span><span class="plk_foot_niming">匿名</span><input type="button"  class="plk_button" value="发布" id="publish" onclick="this.form.submit();"/></div>';
    		}
    
    	}else{
    		if(isset($paramarr[1])){
    			$str .= '<script>alert("用户名或密码错误!")</script>';
    		}
    		$username = '';
    		if(!intval($paramarr[0])){
    			$username = $paramarr[0];
    		}
    		if($_GET['dom'] == 'gv_user_login_comm_view'){
    			//2013-08-22更改评论框样式
    			$str .= '<div id="'.$_GET['dom'].'" class="plk_foot_l_01"><input name="username" id="username" type="text" class="plk_foot_l_input" value="用户名" onclick="	if ($(this).val() == \'用户名\') {$(this).val(\'\');}" onblur="if($(this).val() == \'\') {$(this).val(\'用户名\');}"/><input name="password" id="password" type="password" class="plk_foot_l_input" value="" style="display:none;" onblur="if($(this).val()== this.defaultValue){$(\'#showpass\').show();$(this).hide();}" /><input type="text" value="密码" id="showpass"  class="plk_foot_l_input" onfocus="$(this).hide();$(\'#password\').show().focus();"/> <a class="SINA_login" href="javascript:void(0);" onclick="out_login(\'sina\');">&nbsp;</a><a class="QQ_login" href="javascript:void(0);" onclick="out_login(\'qq\');">&nbsp;</a><a href="'.IConfig::BASE.'/user/reg" class="plk_foot_zc">注册</a></div>';
    			$str .='<div class="plk_foot_r"><span class="plk_foot_checkbox"><input name="anonymous" type="checkbox" value="1" /></span><span class="plk_foot_niming">匿名</span><input type="button"  class="plk_button" value="登录并发布" id="loginpublish" onclick="this.form.submit();"/></div>';
    		}else{
    			$str .= '<div id="'.$_GET['dom'].'">用户名 <input buttonid="button" class="text" type="text" name="username" value="'.$username.'" /> 密码 <input class="text" onfocus="enterSubmit($(this));" buttonid="button" type="password" name="password" /> <input class="n_border" type="button" onclick="user_loginon(\''.$_GET['dom'].$att.'\', \'comm\' ,\'location.reload()\');" id="button" /><input type="button" onclick="window.open(\''.IConfig::BASE.'/user/reg\');" id="button2"/> <a class="SINA_login" href="###" onclick="out_login(\'sina\');">&nbsp;</a><a class="QQ_login" href="###" onclick="out_login(\'qq\');">&nbsp;</a> <script>$("input[name=\'anonymous\']").attr("checked", "checked");</script></div>';
    		}
    	}
    
    	if(!empty($json)){
    		$str = json_encode($str);
    		echo "({data:$str})";
    	}else{
			echo $str;
		}
    }
    //专题登录评论框2013-9-11
	public static function spec_view_login($att = '', $logininfo=''){
    	global $ouser,$json,$paramarr;
    
    	//是否返回json数据
    	if(!empty($json)){
    		$callback = $_GET['jsoncallback'];
    		echo $callback;
    	}else{
    		echo 'true||##';
    	}
    	$str = '';
    
    	if($ouser->isLogin()){
    		import('subdate');
    		$odate = new ISubdate;
			
    		$str .='<div class="plk_foot_l_02">'.$odate->getDayAMPM().'好：<span class="plk_foot_l_name">'.$ouser->getUserName().'</span><a href="'.IConfig::USERURL.'/home/">个人中心</a>|<a href="javascript:quit_quit();">退出</a></div>';
    		$str .='<div class="plk_foot_r"><span class="plk_foot_checkbox"><input name="anonymous" type="checkbox" value="1" /></span><span class="plk_foot_niming">匿名</span><input type="button"  class="plk_button" value="发布" id="publish" onclick="comments_submit(\'comments\');return false;" /></div>';
       	}else{
    		$str .= '<div id="'.$_GET['dom'].'" class="plk_foot_l_01"><input name="username" id="username" type="text" class="plk_foot_l_input" value="用户名" onclick="	if ($(this).val() == \'用户名\') {$(this).val(\'\');}" onblur="if($(this).val() == \'\') {$(this).val(\'用户名\');}"/><input name="password" id="password" type="password" class="plk_foot_l_input" value="" style="display:none;" onblur="if($(this).val()== this.defaultValue){$(\'#showpass\').show();$(this).hide();}" /><input type="text" value="密码" id="showpass"  class="plk_foot_l_input" onfocus="$(this).hide();$(\'#password\').show().focus();"/> <a class="SINA_login" href="javascript:void(0);" onclick="out_login(\'sina\');">&nbsp;</a><a class="QQ_login" href="javascript:void(0);" onclick="out_login(\'qq\');">&nbsp;</a><a href="'.IConfig::BASE.'/user/reg" class="plk_foot_zc">注册</a></div>';
    		$str .= '<div class="plk_foot_r"><span class="plk_foot_checkbox"><input name="anonymous" type="checkbox" value="1" /></span><span class="plk_foot_niming">匿名</span><input type="button"  class="plk_button" value="登录并发布" id="loginpublish" onclick="comments_submit(\'comments\');return false;" /></div>';
    	}
    
    	if(!empty($json)){
    		$str = json_encode($str);
    		echo "({data:$str})";
    	}else{
			echo $str;
		}
    }
    
    
}