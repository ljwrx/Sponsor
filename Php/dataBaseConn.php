<?php
/**
 * Created by JetBrains PhpStorm.
 * User: liaojiawei
 * Date: 13-8-12
 * Time: 下午9:15
 * To change this template use File | Settings | File Templates.
 */
//include_once '../../lib/log4php/log4php.php';

const SP_DBlink = "127.0.0.1:1521/xe";
const SP_HOST = "localhost";
const SP_PORT = "3306";
const SP_DBNAME = "sponsor";
const SP_ACCOUNT ="root";
const SP_PASSWORD ="root";
const SP_CHARSET = "UTF8";

function connectDB($arg_dbname = SP_DBNAME,$arg_host = SP_HOST,$arg_port = SP_PORT,$arg_account = SP_ACCOUNT,$arg_password = SP_PASSWORD,$arg_charset = SP_CHARSET){
    $q_pdo = null;
    try{
        $dsn_con = "mysql:host=".$arg_host.";port=".$arg_port.";dbname=".$arg_dbname;
        $q_pdo = new PDO($dsn_con, $arg_account, $arg_password, array(PDO::ATTR_PERSISTENT => true));
        $q_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }catch (Exception $e){
        throw new InvalidArgumentException($e->getMessage());
    }
    if(!$q_pdo){
        throw new InvalidArgumentException("数据库连接异常 dataBaseConn.php");
    }
    return $q_pdo;
}