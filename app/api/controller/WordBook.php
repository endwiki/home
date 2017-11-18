<?php
/**
 * 单词本接口
 * User: end_wiki
 * Date: 2017/11/19
 * Time: 0:03
 */
namespace app\api\controller;

use app\common\exceptions\WordBookAddFailedException;
use app\common\verifications\WordBookAdd;
use app\api\model\WordBook as WordBookModel;
use src\framework\Request;
use src\framework\Response;

class WordBook extends Authorization {

    /**
     * 新增单词本
     * @method POST
     * @api api/WordBook/add
     * @return mixed
     * @throws WordBookAddFailedException [500001]新增单词本失败
     */
    public function add(){
        $params = Request::post();
        (new WordBookAdd())->eachFields($params);
        $result = WordBookModel::add($params['title'],$params['description'],$this->uid);
        if(!$result){
            throw new WordBookAddFailedException();
        }
        Response::ajaxReturn([
            'code'  =>  500000,
            'message'   =>  '新增单词本成功!',
        ]);
    }
}