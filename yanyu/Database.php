<?php
/**
 * 数据库类
 * User: end_wiki
 * Date: 2017/10/22
 * Time: 19:09
 */
namespace yanyu;

use yanyu\exceptions\ConfigNotFoundException;
use yanyu\exceptions\DatabaseConfigNotFoundException;
use yanyu\exceptions\DatabaseConfigTypeUndefinedException;
use yanyu\exceptions\DatabaseTypeNotFoundException;
use yanyu\databases\DatabaseInterface;

class Database {

    // 支持的数据库类型
    private static $databaseType = ['MySQL','Redis','MongoDB'];
    // 数据库实现类的命名空间
    private static $namespace = 'yanyu\\databases\\';
    // 数据库实例
    private static $instances = [];

    /**
     * 获取数据库实例
     * @param String $dbName 数据库名称
     * @return DatabaseInterface
     * @throws DatabaseTypeNotFoundException [100024]数据库类型不支持异常
     * @throws DatabaseConfigTypeUndefinedException [100030]数据库配置中数据库类型未定义异常
     */
    public static function getInstance(String $dbName){
        $dbConfig = self::getConfig($dbName);
        // 检查是否支持该数据库
        if(!isset($dbConfig['TYPE'])){
            throw new DatabaseConfigTypeUndefinedException();
        }
        if(!in_array($dbConfig['TYPE'],self::$databaseType)){
            throw new DatabaseTypeNotFoundException();
        }
        // 如果实例不存在则实例化
        if(!isset(self::$instances[$dbName])){
            $class = self::$namespace . $dbConfig['TYPE'];
            self::$instances[$dbName] = new $class($dbConfig);
        }
        return self::$instances[$dbName];
    }

    /**
     * 获取数据库默认配置
     * @param String $dbName 数据库名称
     * @return mixed
     * @throws DatabaseConfigNotFoundException [100026]没有找到对应的数据库配置异常
     */
    private static function getConfig(String $dbName){
        try{
            $dbConfig = Config::get(strtoupper($dbName),'DATABASE');
        }catch(ConfigNotFoundException $e){
            throw new DatabaseConfigNotFoundException();
        }
        return $dbConfig;
    }
}
