<?php
namespace app\admin\library;

use app\admin\model\Admin;
use think\facade\Session;
use think\facade\Config;
use \Tree;
class Auth extends \Auth
{
    protected $_error = '';
    protected $logined = false; // 登录状态

    /**
     * 管理员登录
     * 
     * @param  string $useranme 用户名
     * @param  string $password 密码
     * @param  int    $keeptime 有效时长
     * @return boolean
     */
    public function login($username, $password, $keeptime=0)
    {
        $admin = Admin::get(['username' => $username]);
        if (!$admin) {
            $this->setError('Username is incorrect');
            return false;
        }
        // if ($admin['s'])
        if ($admin->password != md5($password))
        {
            $this->setError('Password is incorrect');
            return false;
        }
        Session::set("admin", $admin->toArray());
        return true;


    }
    /**
     * 注销登录
     */
    public function logout()
    {
        $admin = Admin::get(intval($this->id));
        if (!$admin) {
            return true;
        }
        Session::delete("admin");
        return true;
    }
    /**
     * 获取左侧和顶部菜单栏
     * 
     * @param array $params URL对应的badge数据
     * @param string $fixedPage 默认页
     * @return array
     */
    public  function getSidebar($params = [], $fixedPage = 'dashboard')
    {
        $colorArr = ['red','green','yellow','blue'];
        $colorNums = count($colorArr);
        $badgeList = [];
        $module = request()->module();
        
        // 生成菜单的badge
        foreach ($params as $k => $v) {
            $url = $k;
            if (is_array($v)) {
                $nums = isset($v[0]) ? $v[0] : 0;
                $color = isset($v[1]) ? $v[1] : $colorArr[(is_numeric($nums) ? $nums : strlen($nums)) % $colorNums];
                $class = isset($v[2]) ? $v[2] : 'label';
            } else {
                $nums = $v;
                $color = $colorArr[(is_numeric($nums) ? $nums : strlen($nums)) % $colorNums];
                $class = 'label';
            }
            // 必须nums大于0才显示
            if ($nums) {
                $badgeList[$url] = '<small class="' . $class . '">' . $nums . '</small>'; 
            }
        }
        // 读取管理员当前拥有的权限节点
        $uid = Session::get('admin.id');
        $userRule = $this->getRuleList($uid);
        $selected = $referer = [];
        // $ruleList = collection(\app\admin\model\AuthRule::where('ismenu', 1)->select())->toArray();
        $ruleList = \app\admin\model\AuthRule::where('ismenu', 1)->select()->toArray();
        foreach ($ruleList as $k => &$v)
        {
            if (!in_array($v['name'], $userRule)) {
                unset($ruleList[$k]);
                continue;
            }
            $v['url'] = '/' . $module . '/' . $v['name'];
            // $v['title'] 
            $selected = $v['name'] == $fixedPage ? $v : $selected;
            // $referer = url($v['url']) == $refererUrl ? $v : $referer;
        }
        if ($selected == $referer) {
            $referer = [];
        }
        $selected && $selected['url'] = url($selected['url']);
        $referer && $referer['url'] = url($referer['url']);

        $select_id = $selected ? $selected['id'] : 0;
        $menu = $nav = '';
        if (Config::get('multiplenav')) {
        } else {
            // 构造菜单数据
            Tree::instance()->init($ruleList);
            $menu = Tree::instance()->getTreeMenu(0, '<li class="treeview @class"><a href="@url@addtabs" addtabs="@id" url="@url" py="@py" pinyin="@pinyin"><i class="@icon"></i> <span>@title</span> <span class="pull-right-container">@caret @badge</span></a> @childlist</li>', $select_id, '', 'ul', 'class="treeview-menu"');
            if ($selected) {
                $nav .= '<li role="presentation" id="tab_' . $selected['id'] . '" class="' . ($referer ? '' : 'active') . '"><a href="#con_' . $selected['id'] . '" node-id="' . $selected['id'] . '" aria-controls="' . $selected['id'] . '" role="tab" data-toggle="tab"><i class="' . $selected['icon'] . ' fa-fw"></i> <span>' . $selected['title'] . '</span> </a></li>';
            }
            if ($referer) {
                $nav .= '<li role="presentation" id="tab_' . $referer['id'] . '" class="active"><a href="#con_' . $referer['id'] . '" node-id="' . $referer['id'] . '" aria-controls="' . $referer['id'] . '" role="tab" data-toggle="tab"><i class="' . $referer['icon'] . ' fa-fw"></i> <span>' . $referer['title'] . '</span> </a> <i class="close-tab fa fa-remove"></i></li>';
            }
        }
        return [$menu, $nav, $nav, $selected, $referer];
    }

    /**
     * 检测当前控制器和方法是否匹配传递的数组
     * 
     * @param array $arr 需要验证权限的数组
     * @return bool
     */
    public function match($arr = [])
    {
        $arr = is_array($arr) ? $arr : explode(',', $arr);
        if (!$arr) {
            return FALSE;
        }

        $arr = array_map('strtolower', $arr);
        // 是否存在
        if (in_array(strtolower(Request::action()), $arr) || in_array('*', $arr)) {
            return TRUE;
        }

        // 没找到匹配
        return FALSE;
    }
    /**
     * 设置错误信息
     *
     * @param string $error 错误信息
     * @return Auth
     */
    public function setError($error)
    {
        $this->_error = $error;
        return $this;
    }

    /**
     * 获取错误信息
     * @return string
     */
    public function getError()
    {
        return $this->_error ? ($this->_error) : '';
    }
}