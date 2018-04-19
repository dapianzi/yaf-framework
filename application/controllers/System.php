<?php
/**
 * Created by PhpStorm.
 * @Author Carl
 * @since 2018/4/3 17:38
 */
class SystemController extends BaseController {

    public $menu_icon = array(
        'home', 'set', 'auz', 'fire', 'diamond', 'location','read', 'survey',
        'download', 'component', 'shezhi1', 'yinqing', 'star', 'chat', 'list',
        'tubiao', 'tree', 'xuanzemoban48', 'gongju', 'wenjian', 'layouts',
        'user', 'jilu', 'unlink', 'senior'
    );

    public function indexAction(){

    }

    public function menuAction(){
        $this->assign('icons', json_encode($this->menu_icon));
    }

    public function menuListAction(){
        $menu_list = (new MenuModel())->getAllMenuList(ROLE_SUPERADMIN);
        if(count($menu_list)<=0){
            return gf_ajax_error('无菜单');
        }
        $menu_list = $this->_renderMenuList($menu_list);
        return gf_ajax_success($menu_list, array('count'=> count($menu_list)));
    }

    private function _renderMenuList($menu_list, $key='0', $level=0) {
        $ret = [];
        if (isset($menu_list[$key])) {
            foreach ($menu_list[$key] as $m) {
                $m['level'] = str_pad('├', 15-$level*3, '╌', STR_PAD_RIGHT);
                $ret[] = $m;
                $ret = array_merge($ret, $this->_renderMenuList($menu_list, $m['id'], $level+2));
            }
        }
        return $ret;
    }

    /**
     * 更改菜单显示隐藏
     */
    function menuStateAction(){
        $id = $this->getPost('id', 0);
        $type = $this->getPost('type', '');
        $checked = $this->getPost('checked', '');

        switch ($type) {
            case 'status':
                $value = strtolower($checked)=='true' ? 0 : -1;
                break;
            case 'is_show':
                $value = strtolower($checked)=='true' ? 1 : 0;
                break;
            default:
                return gf_ajax_error('参数错误！');
        }
        $MenuModel = new MenuModel();
        $res = $MenuModel->set($id, $type, $value);
        if ($res) {
            return gf_ajax_success('修改成功');
        }else{
            return gf_ajax_error('修改失败');
        }
    }

    /**
     * 删除菜单
     */
    public function menuDelAction(){
        $idx = $this->getPost('id');
        $ids = explode(',', $idx);
        if (count($ids) <= 0) {
            gf_ajax_success('参数错误！');
        }
        gf_ajax_success((new MenuModel())->del($ids));
    }

    /**
     * 添加/修改菜单
     */
    public function menuSaveAction(){
        $MenuModel = new MenuModel();
        $id = $this->getPost('id', 0);
        $is_show = $this->getPost('is_show');
        $status = $this->getPost('status');
        $data = array(
            'pid' => $this->getPost('pid', 0),
            'node' => $this->getPost('node'),
            'icon' => $this->getPost('icon'),
            'href' => $this->getPost('href'),
            'perm_route' => $this->getPost('perm_route'),
            'list_order' => floatval($this->getPost('list_order')),
            'is_show' => $is_show=='on' ? 1 : 0, // 我就是我，是颜色不一样的焰火
            'status' => $status=='on' ? STATUS_OK : STATUS_NO_USE,
        );
        // valid unique name
        if ($MenuModel->getColumn("SELECT id FROM menu WHERE id<>? AND node=?", array($id, $data['node'])) > 0) {
            gf_ajax_error(sprintf('已经存在 [%s] 的菜单了', $data['node']));
        }

        // add
        if ($id == 0) {
            $id = $MenuModel->add($data);
            if ($id > 0) {
                if (empty($data['list_order'])) {
                    $MenuModel->set($id, 'list_order', $id);
                }
                gf_ajax_success($id);
            } else {
                gf_ajax_error('添加失败');
            }
        }
        // modify
        else {
            $res = $MenuModel->mod($id, $data);
            if($res){
                return gf_ajax_success('添加成功');
            }else{
                return gf_ajax_error('添加失败');
            }
        }
    }

    /*
     * admin list
     */
    public function adminAction() {
        $roles = (new RoleModel())->getKeyValue("SELECT id,role FROM roles WHERE status=?", array(STATUS_OK));
        $this->assign('roles', $roles);
    }

    public function adminListAction() {
        $is_super_admin = $this->user['role_id']==ROLE_SUPERADMIN ? TRUE : FALSE;
        $admin = (new UserModel())->getUsers($is_super_admin);
        gf_ajax_success($admin, array('count'=> count($admin)));
    }

    public function adminSaveAction() {
        $id = $this->getPost('id', 0);
        $status = $this->getPost('status');
        $data = array(
            'email' => $this->getPost('email'),
            'nickname' => $this->getPost('nickname'),
            'role_id' => $this->getPost('role', 0),
            'status' => $status == 'on' ? STATUS_OK : STATUS_NO_USE,
        );
        $userModel = new UserModel();
        if ($id > 0) {
            // create
            $username = $this->getPost('username');
            $password = $this->getPost('password');
            if ($userModel->getColumn("SELECT id FROM user WHERE username=?", array($username)) > 0) {
                gf_ajax_error(sprintf('用户 [%s] 已存在', $username));
            }
            // random a salt str
            $salt = gf_rand_str(5);
            $data['username'] = $username;
            $data['salt'] = $salt;
            $data['password'] = gf_encrypt_pwd($password, $salt);
            $id = $userModel->add($data);
            if ($id > 0) {
                gf_ajax_success($id);
            } else {
                gf_ajax_error('添加失败');
            }
        } else {
            // update
            $res = $userModel->mod($id, $data);
            gf_ajax_success($res);
        }
    }

    public function adminStateAction() {
        $id = $this->getPost('id', 0);
        $status = $this->getPost('status');
        $res = (new UserModel())->set($id, 'status', $status=='on'?STATUS_OK:STATUS_NO_USE);
        gf_ajax_success($res);
    }

    public function adminRoleAction() {
        $action = $this->getPost('action');
        $id = $this->getPost('id', 0);
        switch ($action) {
            case 'add':
                break;
            case 'set':
                break;
            case 'del':

        }
    }

    /*
     * permissions
     */
    public function permissionAction() {
        $roles = (new RoleModel())->getAll("SELECT id,role FROM roles WHERE status=", array(STATUS_OK));
        $this->assign('role', $roles);
    }

    public function permListAction() {
        $permissions = (new RoleModel())->getPermissions();
        gf_ajax_success($permissions, array('count'=>$permissions));
    }

    public function permUpdateAction() {
        $role = $this->getPost('role', 0);
        $type = $this->getPost('type');
        $perm = $this->getPost('perm');

        $perm = array_unique(array_map(function($v){return intval($v);}, explode(',', $perm)));
        $type = $type == 'denied' ? 'denied' : 'access';
        $data = array(
            'permissions' => json_encode(array($type => $perm))
        );
        $res = (new RoleModel())->mod($role, $data);
        gf_ajax_success($res);
    }


    /*
     * Logs
     */
    public function logsAction() {}

    public function logsListAction() {
        $page = $this->getQuery('p', 1);
        $limit = $this->getQuery('limit', 50);
        $from = $this->getQuery('from');
        $to = $this->getQuery('to');

        if (!preg_match("/^\d{4}(-\d{2}){2} [12]\d(:[0-5]\d){2}$/", $from)) {
            $from = gf_now(time()-30*86400);
        }
        if (!preg_match("/^\d{4}(-\d{2}){2} [12]\d(:[0-5]\d){2}$/", $to)) {
            $to = gf_now();
        }
        if ((strtotime($to) - strtotime($from)) > 90*86400) {
            gf_ajax_error('超出了可查询的时间范围');
        }
        $userModel = new UserModel();
        $logs = $userModel->getUserLogs($from, $to, $page, $limit);
        return gf_ajax_success($logs, array('count' => count($logs)));
    }

    public function logsCleanAction() {
        $LogModel = new UserModel();
        $res = $LogModel->setUserLogsExpire();
        if($res){
            return gf_ajax_success('清理成功');
        }else{
            return gf_ajax_error('清理失败');
        }
    }
}
