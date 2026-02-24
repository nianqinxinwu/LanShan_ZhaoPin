<?php

namespace app\admin\model\zpwxsys;
use think\Model;
use think\Db;

class Agent extends Model
{
    protected $name = 'zpwxsys_agent';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;


    /**
     * 根据搜索条件获取文章列表信息
     * @author
     */
    public function getAgentByWhere($map, $Nowpage, $limits,$od)
    {
        return $this->where($map)->page($Nowpage, $limits)->order($od)->select();

    }


    public function getAgentCount($map)
    {
        return $this->where($map)->count();

    }



    public function updateAgent($param)
    {
        Db::startTrans();// 启动事务
        try{
            $this->allowField(true)->save($param, ['id' => $param['id']]);
            Db::commit();// 提交事务
            //writelog(session('uid'),session('username'),'文章【'.$param['title'].'】编辑成功',1);
            return ['code' => 200, 'data' => '', 'msg' => '经纪人编辑成功'];
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            //writelog(session('uid'),session('username'),'文章【'.$param['title'].'】编辑失败',2);
            return ['code' => 100, 'data' => '', 'msg' => '经纪人编辑失败'];
        }
    }



    /**
     * [getOneArticle 根据文章id获取一条信息]
     * @author
     */
    public function getOneAgent($id)
    {
        return $this->where('id', $id)->find();
    }


    public function getOneAgentByWhere($map)
    {
        return $this->where($map)->find();
    }


    /**
     * [delArticle 删除文章]
     * @author
     */
    public function delAgent($id)
    {
        $title = $this->where('id',$id)->value('name');
        Db::startTrans();// 启动事务
        try{
            $this->where('id', $id)->delete();
            Db::commit();// 提交事务
            //writelog(session('uid'),session('username'),'文章【'.$title.'】删除成功',1);
            return ['code' => 200, 'data' => '', 'msg' => '经纪人删除成功'];
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            //writelog(session('uid'),session('username'),'文章【'.$title.'】删除失败',2);
            return ['code' => 100, 'data' => '', 'msg' =>  '经纪人删除失败'];
        }
    }

    /**
     * batchDelArticle 批量删除文章
     * @param $param
     * @return array
     */
    public function batchDelAgent($param){
        Db::startTrans();// 启动事务
        try{
            ArticleModel::destroy($param);
            Db::commit();// 提交事务
            //    writelog(session('uid'),session('username'),'文章批量删除成功',1);
            return ['code' => 200, 'data' => '', 'msg' => '经纪人删除成功'];
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            //  writelog(session('uid'),session('username'),'文章批量删除失败',1);
            return ['code' => 100, 'data' => '', 'msg' => '经纪人删除失败'];
        }
    }

    /**
     * [articleState 文章状态]
     * @param $param
     * @return array
     */
    public function agentState($id,$num){
        $title = $this->where('id',$id)->value('name');
        if($num == 2){
            $msg = '禁用';
        }else{
            $msg = '启用';
        }
        Db::startTrans();// 启动事务
        try{
            $this->where('id',$id)->setField(['status'=>$num]);
            Db::commit();// 提交事务
            //    writelog(session('uid'),session('username'),'文章【'.$title.'】'.$msg.'成功',1);
//                return ['code' => 200, 'data' => '', 'msg' => '已'.$msg];
        }catch( \Exception $e){
            Db::rollback();// 回滚事务
            //  writelog(session('uid'),session('username'),'文章【'.$title.'】'.$msg.'失败',2);
            return ['code' => 100, 'data' => '', 'msg' => $msg.'失败'];
        }
    }

    /**
     * 批量禁用文章
     * @param $param
     * @return array
     */
    public function forbiddenArticle($param){
        Db::startTrans();// 启动事务
        try{
            if($param){
                $this->saveAll($param);
            }else{
                $this->where('1=1')->update(['status'=>2]);
            }
            Db::commit();// 提交事务
            writelog(session('uid'),session('username'),'批量禁用文章成功',1);
            return ['code' => 200, 'data' => '', 'msg' => '批量禁用成功'];
        }catch( \Exception $e){
            Db::rollback ();//回滚事务
            writelog(session('uid'),session('username'),'批量禁用文章失败',2);
            return ['code' => 100, 'data' => '', 'msg' => '批量禁用失败'];
        }
    }

    /**
     * 批量启用文章
     * @param $param
     * @return array
     */
    public function usingArticle($param){
        Db::startTrans();// 启动事务
        try{
            if($param){
                $this->saveAll($param);
            }else{
                $this->where('1=1')->update(['status'=>1]);
            }
            Db::commit();// 提交事务
            writelog(session('uid'),session('username'),'批量启用文章成功',1);
            return ['code' => 200, 'data' => '', 'msg' => '批量启用成功'];
        }catch( \Exception $e){
            Db::rollback ();//回滚事务
            writelog(session('uid'),session('username'),'批量启用文章失败',2);
            return ['code' => 100, 'data' => '', 'msg' => '批量启用失败'];
        }
    }

}