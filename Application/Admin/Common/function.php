<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 后台公共文件
 * 主要定义后台公共函数库
 */
require_once(APP_PATH . '/Admin/Common/cloud.php');
/* 解析列表定义规则*/

function get_list_field($data, $grid, $model)
{

    // 获取当前字段数据
    foreach ($grid['field'] as $field) {
        $array = explode('|', $field);
        $temp = $data[$array[0]];
        // 函数支持
        if (isset($array[1])) {
            $temp = call_user_func($array[1], $temp);
        }
        $data2[$array[0]] = $temp;
    }
    if (!empty($grid['format'])) {
        $value = preg_replace_callback('/\[([a-z_]+)\]/', function ($match) use ($data2) {
            return $data2[$match[1]];
        }, $grid['format']);
    } else {
        $value = implode(' ', $data2);
    }

    // 链接支持
    if (!empty($grid['href'])) {
        $links = explode(',', $grid['href']);
        foreach ($links as $link) {
            $array = explode('|', $link);
            $href = $array[0];
            if (preg_match('/^\[([a-z_]+)\]$/', $href, $matches)) {
                $val[] = $data2[$matches[1]];
            } else {
                $show = isset($array[1]) ? $array[1] : $value;
                // 替换系统特殊字符串
                $href = str_replace(
                    array('[DELETE]', '[EDIT]', '[MODEL]'),
                    array('del?ids=[id]&model=[MODEL]', 'edit?id=[id]&model=[MODEL]', $model['id']),
                    $href);

                // 替换数据变量
                $href = preg_replace_callback('/\[([a-z_]+)\]/', function ($match) use ($data) {
                    return $data[$match[1]];
                }, $href);

                $val[] = '<a href="' . U($href) . '">' . $show . '</a>';
            }
        }
        $value = implode(' ', $val);
    }
    return $value;
}

// 获取模型名称
function get_model_by_id($id)
{
    return $model = M('Model')->getFieldById($id, 'title');
}

// 获取属性类型信息
function get_attribute_type($type = '')
{
    // TODO 可以加入系统配置
    static $_type = array(
        'num' => array('数字', 'int(10) UNSIGNED NOT NULL'),
        'string' => array('字符串', 'varchar(255) NOT NULL'),
        'textarea' => array('文本框', 'text NOT NULL'),
        'datetime' => array('时间', 'int(10) NOT NULL'),
        'bool' => array('布尔', 'tinyint(2) NOT NULL'),
        'select' => array('枚举', 'char(50) NOT NULL'),
        'radio' => array('单选', 'char(10) NOT NULL'),
        'checkbox' => array('多选', 'varchar(100) NOT NULL'),
        'editor' => array('编辑器', 'text NOT NULL'),
        'picture' => array('上传图片', 'int(10) UNSIGNED NOT NULL'),
        'file' => array('上传附件', 'int(10) UNSIGNED NOT NULL'),
    );
    return $type ? $_type[$type][0] : $_type;
}

/**
 * 获取对应状态的文字信息
 * @param int $status
 * @return string 状态文字 ，false 未获取到
 * @author huajie <banhuajie@163.com>
 */
function get_status_title($status = null)
{
    if (!isset($status)) {
        return false;
    }
    switch ($status) {
        case -1 :
            return L('_DELETED_');
            break;
        case 0  :
            return L('_DISABLE_');
            break;
        case 1  :
            return L('_NORMAL_');
            break;
        case 2  :
            return L('_PENDING_AUDIT_');
            break;
        default :
            return false;
            break;
    }
}

// 获取数据的状态操作
function show_status_op($status)
{
    switch ($status) {
        case 0  :
            return L('_ENABLE_');
            break;
        case 1  :
            return L('_DISABLE_');
            break;
        case 2  :
            return L('_AUDIT_');
            break;
        default :
            return false;
            break;
    }
}

/**
 * 获取文档的类型文字
 * @param string $type
 * @return string 状态文字 ，false 未获取到
 * @author huajie <banhuajie@163.com>
 */
function get_document_type($type = null)
{
    if (!isset($type)) {
        return false;
    }
    switch ($type) {
        case 1  :
            return L('_DIRECTORY_');
            break;
        case 2  :
            return L('_THEME_');
            break;
        case 3  :
            return L('_PARAGRAPH_');
            break;
        default :
            return false;
            break;
    }
}

/**
 * 获取配置的类型
 * @param string $type 配置类型
 * @return string
 */
function get_config_type($type = 0)
{
    $list = C('CONFIG_TYPE_LIST');
    return $list[$type];
}

/**
 * 获取配置的分组
 * @param string $group 配置分组
 * @return string
 */
function get_config_group($group = 0)
{
    $list = C('CONFIG_GROUP_LIST');
    return $group ? $list[$group] : '';
}

/**
 * select返回的数组进行整数映射转换
 *
 * @param array $map 映射关系二维数组  array(
 *                                          '字段名1'=>array(映射关系数组),
 *                                          '字段名2'=>array(映射关系数组),
 *                                           ......
 *                                       )
 * @author 朱亚杰 <zhuyajie@topthink.net>
 * @return array
 *
 *  array(
 *      array('id'=>1,'title'=>'标题','status'=>'1','status_text'=>'正常')
 *      ....
 *  )
 *
 */
function int_to_string(&$data, $map = array('status' => array(1 => '正常', -1 => '删除', 0 => '禁用', 2 => '未审核', 3 => '草稿')))
{
    if ($data === false || $data === null) {
        return $data;
    }
    $data = (array)$data;
    foreach ($data as $key => $row) {
        foreach ($map as $col => $pair) {
            if (isset($row[$col]) && isset($pair[$row[$col]])) {
                $data[$key][$col . '_text'] = $pair[$row[$col]];
            }
        }
    }
    return $data;
}

function lists_plus(&$data)
{
    $alias = M('module')->select();
    foreach ($alias as $value) {
        $alias_set[$value['name']] = $value['alias'];
    }
    foreach ($data as $key => $value) {
        $data[$key]['alias'] = $alias_set[$data[$key]['module']];

        $mid = M('action_log')->field("max(create_time),remark")->where('action_id=' . $data[$key]['id'])->select();
        $mid_s = $mid[0]['remark'];
        if( isset($mid_s) && strpos($mid_s , L('_INTEGRAL_')) !== false)
        {
        $data[$key]['vary'] = $mid_s;
        }

    }
    return $data;
}

/**
 * 动态扩展左侧菜单,base.html里用到
 * @author 朱亚杰 <zhuyajie@topthink.net>
 */
function extra_menu($extra_menu, &$base_menu)
{
    foreach ($extra_menu as $key => $group) {
        if (isset($base_menu['children'][$key])) {
            $base_menu['children'][$key] = array_merge($base_menu['children'][$key], $group);
        } else {
            $base_menu['children'][$key] = $group;
        }
    }
}

/**
 * 动态扩展左侧插件菜单,base.html里用到
 * @param $base_menu
 * @author 郑钟良<zzl@ourstu.com>
 */
function extra_addons_menu(&$base_menu)
{
    $extra_menu=array(L('_ALREADY_INSTALLED_IN_THE_BACKGROUND_') => D('Admin/Addons')->getAdminList(),);
    foreach ($extra_menu as $key => $group) {
        if (isset($base_menu['children'][$key])) {
            $base_menu['children'][$key] = array_merge($base_menu['children'][$key], $group);
        } else {
            $base_menu['children'][$key] = $group;
        }
    }
}

/**
 * 获取参数的所有父级分类
 * @param int $cid 分类id
 * @return array 参数分类和父类的信息集合
 * @author huajie <banhuajie@163.com>
 */
function get_parent_category($cid)
{
    if (empty($cid)) {
        return false;
    }
    $cates = M('Category')->where(array('status' => 1))->field('id,title,pid')->order('sort')->select();
    $child = get_category($cid);    //获取参数分类的信息
    $pid = $child['pid'];
    $temp = array();
    $res[] = $child;
    while (true) {
        foreach ($cates as $key => $cate) {
            if ($cate['id'] == $pid) {
                $pid = $cate['pid'];
                array_unshift($res, $cate);    //将父分类插入到数组第一个元素前
            }
        }
        if ($pid == 0) {
            break;
        }
    }
    return $res;
}

/**
 * 检测验证码
 * @param  String $id 验证码ID
 * @return boolean     检测结果
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function check_verify($code, $id = "admin")
{
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}

/**
 * 获取当前分类的文档类型
 * @param int $id
 * @return array 文档类型数组
 * @author huajie <banhuajie@163.com>
 */
function get_type_bycate($id = null)
{
    if (empty($id)) {
        return false;
    }
    $type_list = C('DOCUMENT_MODEL_TYPE');
    $model_type = M('Category')->getFieldById($id, 'type');
    $model_type = explode(',', $model_type);
    foreach ($type_list as $key => $value) {
        if (!in_array($key, $model_type)) {
            unset($type_list[$key]);
        }
    }
    return $type_list;
}

/**
 * 获取当前文档的分类
 * @param int $id
 * @return array 文档类型数组
 * @author huajie <banhuajie@163.com>
 */
function get_cate($cate_id = null)
{
    if (empty($cate_id)) {
        return false;
    }
    $cate = M('Category')->where('id=' . $cate_id)->getField('title');
    return $cate;
}

// 分析枚举类型配置值 格式 a:名称1,b:名称2
function parse_config_attr($string)
{
    $array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
    if (strpos($string, ':')) {
        $value = array();
        foreach ($array as $val) {
            list($k, $v) = explode(':', $val);
            $value[$k] = $v;
        }
    } else {
        $value = $array;
    }
    return $value;
}

// 获取子文档数目
function get_subdocument_count($id = 0)
{
    return M('Document')->where('pid=' . $id)->count();
}


// 分析枚举类型字段值 格式 a:名称1,b:名称2
// 暂时和 parse_config_attr功能相同
// 但请不要互相使用，后期会调整
function parse_field_attr($string)
{
    if (0 === strpos($string, ':')) {
        // 采用函数定义
        return eval(substr($string, 1) . ';');
    }
    $array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
    if (strpos($string, ':')) {
        $value = array();
        foreach ($array as $val) {
            list($k, $v) = explode(':', $val);
            $value[$k] = $v;
        }
    } else {
        $value = $array;
    }
    return $value;
}

/**
 * 获取行为数据
 * @param string $id 行为id
 * @param string $field 需要获取的字段
 * @author huajie <banhuajie@163.com>
 */
function get_action($id = null, $field = null)
{
    if (empty($id) && !is_numeric($id)) {
        return false;
    }
    $list = S('action_list');
    if (empty($list[$id])) {
        $map = array('status' => array('gt', -1), 'id' => $id);
        $list[$id] = M('Action')->where($map)->field(true)->find();
    }
    return empty($field) ? $list[$id] : $list[$id][$field];
}

/**
 * 根据条件字段获取数据
 * @param mixed $value 条件，可用常量或者数组
 * @param string $condition 条件字段
 * @param string $field 需要返回的字段，不传则返回整个数据
 * @author huajie <banhuajie@163.com>
 */
function get_document_field($value = null, $condition = 'id', $field = null)
{
    if (empty($value)) {
        return false;
    }

    //拼接参数
    $map[$condition] = $value;
    $info = M('Model')->where($map);
    if (empty($field)) {
        $info = $info->field(true)->find();
    } else {
        $info = $info->getField($field);
    }
    return $info;
}

/**
 * 获取行为类型
 * @param intger $type 类型
 * @param bool $all 是否返回全部类型
 * @author huajie <banhuajie@163.com>
 */
function get_action_type($type, $all = false)
{
    $list = array(
        1 => L('_SYSTEM_'),
        2 => L('_USER_'),
    );
    if ($all) {
        return $list;
    }
    return $list[$type];
}

function formatLog($log)
{
    $log = explode("\r\n", $log);
    $log = '<li>' . implode('</li><li>', $log) . '</li>';
    return $log;
}

function adminU($url = '', $vars = '', $suffix = true, $domain = false)
{
    // 解析URL
    $info = parse_url($url);
    $url = !empty($info['path']) ? $info['path'] : ACTION_NAME;
    if (isset($info['fragment'])) { // 解析锚点
        $anchor = $info['fragment'];
        if (false !== strpos($anchor, '?')) { // 解析参数
            list($anchor, $info['query']) = explode('?', $anchor, 2);
        }
        if (false !== strpos($anchor, '@')) { // 解析域名
            list($anchor, $host) = explode('@', $anchor, 2);
        }
    } elseif (false !== strpos($url, '@')) { // 解析域名
        list($url, $host) = explode('@', $info['path'], 2);
    }
    // 解析子域名
    if (isset($host)) {
        $domain = $host . (strpos($host, '.') ? '' : strstr($_SERVER['HTTP_HOST'], '.'));
    } elseif ($domain === true) {
        $domain = $_SERVER['HTTP_HOST'];
        if (C('APP_SUB_DOMAIN_DEPLOY')) { // 开启子域名部署
            $domain = $domain == 'localhost' ? 'localhost' : 'www' . strstr($_SERVER['HTTP_HOST'], '.');
            // '子域名'=>array('模块[/控制器]');
            foreach (C('APP_SUB_DOMAIN_RULES') as $key => $rule) {
                $rule = is_array($rule) ? $rule[0] : $rule;
                if (false === strpos($key, '*') && 0 === strpos($url, $rule)) {
                    $domain = $key . strstr($domain, '.'); // 生成对应子域名
                    $url = substr_replace($url, '', 0, strlen($rule));
                    break;
                }
            }
        }
    }

    // 解析参数
    if (is_string($vars)) { // aaa=1&bbb=2 转换成数组
        parse_str($vars, $vars);
    } elseif (!is_array($vars)) {
        $vars = array();
    }
    if (isset($info['query'])) { // 解析地址里面参数 合并到vars
        parse_str($info['query'], $params);
        $vars = array_merge($params, $vars);
    }

    // URL组装
    $depr = C('URL_PATHINFO_DEPR');
    $urlCase = C('URL_CASE_INSENSITIVE');
    if ($url) {
        if (0 === strpos($url, '/')) { // 定义路由
            $route = true;
            $url = substr($url, 1);
            if ('/' != $depr) {
                $url = str_replace('/', $depr, $url);
            }
        } else {
            if ('/' != $depr) { // 安全替换
                $url = str_replace('/', $depr, $url);
            }
            // 解析模块、控制器和操作
            $url = trim($url, $depr);
            $path = explode($depr, $url);
            $var = array();
            $varModule = C('VAR_MODULE');
            $varController = C('VAR_CONTROLLER');
            $varAction = C('VAR_ACTION');
            $var[$varAction] = !empty($path) ? array_pop($path) : ACTION_NAME;
            $var[$varController] = !empty($path) ? array_pop($path) : CONTROLLER_NAME;
            if ($maps = C('URL_ACTION_MAP')) {
                if (isset($maps[strtolower($var[$varController])])) {
                    $maps = $maps[strtolower($var[$varController])];
                    if ($action = array_search(strtolower($var[$varAction]), $maps)) {
                        $var[$varAction] = $action;
                    }
                }
            }
            if ($maps = C('URL_CONTROLLER_MAP')) {
                if ($controller = array_search(strtolower($var[$varController]), $maps)) {
                    $var[$varController] = $controller;
                }
            }
            if ($urlCase) {
                $var[$varController] = parse_name($var[$varController]);
            }
            $module = '';

            if (!empty($path)) {
                $var[$varModule] = implode($depr, $path);
            } else {
                if (C('MULTI_MODULE')) {
                    if (MODULE_NAME != C('DEFAULT_MODULE') || !C('MODULE_ALLOW_LIST')) {
                        $var[$varModule] = MODULE_NAME;
                    }
                }
            }
            if ($maps = C('URL_MODULE_MAP')) {
                if ($_module = array_search(strtolower($var[$varModule]), $maps)) {
                    $var[$varModule] = $_module;
                }
            }
            if (isset($var[$varModule])) {
                $module = $var[$varModule];
                unset($var[$varModule]);
            }

        }
    }

    if (isset($route)) {
        $url = __APP__ . '/' . rtrim($url, $depr);
    } else {
        $module = (defined('BIND_MODULE') && BIND_MODULE == $module) ? '' : $module;
        $url = __APP__ . '/' . ($module ? $module . MODULE_PATHINFO_DEPR : '') . implode($depr, array_reverse($var));
    }
    if ($urlCase) {
        $url = strtolower($url);
    }
    if (!empty($vars)) { // 添加参数
        foreach ($vars as $var => $val) {
            if ('' !== trim($val)) $url .= $depr . $var . $depr . urlencode($val);
        }
    }
    if ($suffix) {
        $suffix = $suffix === true ? C('URL_HTML_SUFFIX') : $suffix;
        if ($pos = strpos($suffix, '|')) {
            $suffix = substr($suffix, 0, $pos);
        }
        if ($suffix && '/' != substr($url, -1)) {
            $url .= '.' . ltrim($suffix, '.');
        }
    }

    if (isset($anchor)) {
        $url .= '#' . $anchor;
    }
    if ($domain) {
        $url = (is_ssl() ? 'https://' : 'http://') . $domain . $url;
    }
    return $url;
}

/**
 * 删除驱动上传的文件
 * @param $file_name 文件名，不带前面路径
 * @param $driver 七牛、sae等
 * @return bool
 */
function delete_driver_upload_file($file_name,$driver)
{
    $return=array('status'=>0,'info'=>'本地文件，不能调用该函数');
    if($driver=='local'){
        return $return;
    }
    $class = get_addon_class($driver);
    if (class_exists($class)) {
        $class=new $class;
        if(method_exists($class,'deleteFile')){
            //todo 七牛删除文件方法已经做了，sae的没有做，要做到sae插件中
            if($class->deleteFile($file_name)){//执行删除远端文件
                $return['info']='删除成功！';
                $return['status']=1;
            }else{
                $return['info']='删除失败！';
            }
        }else{
            $return['info']='删除远端文件方法不存在！';
        }
    } else {
        $return['info']='删除远端文件驱动不存在！';
    }
    return $return;
}

function get_user_crowd_post($status)
{
    if ($status!=-1) {
        return '是';
    } else {
        return '否';
    }
}

/**短息行为列表页切割信息，优化展示
 * @param $data
 * @param int $length
 * @param string $code
 * @return string
 * @author szh(施志宏) szh@ourstu.com
 */
function cut_sms_info($data, $length=57, $code='utf-8'){
    if (is_string($data) && mb_strlen($data, $code)>$length) {
        $data = mb_substr($data, 0, $length, $code) . '...' ;
    }
    return $data ;
}

/**后台编辑器管理页面提示
 * @param $name
 * @return string
 * @author szh(施志宏) szh@ourstu.com
 */
function switch_name($name) {
    switch ($name) {
        case 'EVENT_ADD':
            $tip['tip'] = '前台活动增加页面编辑器' ;
            $tip['url'] = U('Event/index/add') ;
            break;
        case 'GROUP_ADD':
            $tip['tip'] = '前台群组发帖页面编辑器' ;
            $tip['url'] = U('Group/index/edit') ;
            break;
        case 'GROUP_REPLY':
            $tip['tip'] = '前台群组详情页页面编辑器' ;
            $tip['url'] = U('Group/index/detail') ;
            break;
        case 'NEWS_ADD':
            $tip['tip'] = '前台资讯投稿页面编辑器' ;
            $tip['url'] = U('News/index/edit') ;
            break;
        case 'ISSUE_ADD':
            $tip['tip'] = '前台专辑发布页面编辑器' ;
            $tip['url'] = U('Issue/index/index') ;
            break;
        case 'PAPER_ADMIN_ADD':
            $tip['tip'] = '后台文章发布页面编辑器' ;
            $tip['url'] = U('Admin/paper/editpaper') ;
            break;
        case 'SHOP_ADMIN_ADD':
            $tip['tip'] = '后台商城商品增加页面编辑器' ;
            $tip['url'] = U('Admin/shop/goodsedit') ;
            break;
        case 'NEWS_ADMIN_ADD':
            $tip['tip'] = '后台资讯增加页面编辑器' ;
            $tip['url'] = U('Admin/news/editnews') ;
            break;
        case 'FORUM_ADMIN_REPLY':
            $tip['tip'] = '后台论坛回复编辑页面编辑器' ;
            $tip['url'] = U('Admin/forum/replymanager') ;
            break;
        case 'FORUM_ADMIN_ADD':
            $tip['tip'] = '后台论坛帖子编辑页面编辑器' ;
            $tip['url'] = U('Admin/forum/post') ;
            break;
        case 'FORUM_ADD':
            $tip['tip'] = '前台论坛增加页面编辑器' ;
            $tip['url'] = U('forum/index/edit') ;
            break;
        case 'FORUM_REPLY':
            $tip['tip'] = '前台论坛详情页编辑器' ;
            $tip['url'] = U('forum/index/edit') ;
            break;
        case 'DEFAULT':
            $tip['tip'] = '默认编辑器配置' ;
            break;
        default :
            $tip['tip'] = '自定义编辑器-'.$name ;
            break;
    }
    $return[0] = $tip;
    $return[1] = judge_group($name);
    return $return ;
}

function judge_group($name){
    $group = array(
        array('EVENT', '活动编辑器'),
        array('ISSUE', '专辑编辑器'),
        array('FORUM', '论坛编辑器'),
        array('NEWS' , '资讯编辑器'),
        array('GROUP', '群组编辑器'),
        array('SHOP' , '商城编辑器'),
        array('PAPER', '文章编辑器'),
        array('DEFAULT', '默认编辑器配置')
    );
    $return = array() ;
    foreach ($group as $val) {
        if(preg_match('/'.$val[0].'/', $name)){
            $return = $val ;
            break ;
        }
    }
    unset($val) ;
    if(empty($return)){
        $return = array('CUSTOM', '自定义编辑器') ;
    }
    return $return ;
}