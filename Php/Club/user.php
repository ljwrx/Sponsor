<?php

require __DIR__ . "/../../Lib/Slim/Slim.php";
include  "../dataBaseConn.php";

session_start();
$isUserLoggedIn = (array_key_exists("userLoggedin", $_SESSION) && strlen($_SESSION['userLoggedin']) > 0);
if ($isUserLoggedIn) {
    $isUserLoggedIn = $_SESSION['userLoggedin'];
}

\Slim\Slim::registerAutoloader();
$q_pdo = connectDB();
$q_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$app = new \Slim\Slim();

$app->post("/user/register", function () {
    $msg = "具体问题请联系管理员";
    $status = 0;
    global $q_pdo;
    try
    {
        $request = \Slim\Slim::getInstance()->request();
        $body = $request->getBody();
        $user = json_decode($body, true);
        $userPwd = hash("md5",$user["registerPwd"]);

        do {
            $sql = "select COUNT(account) accountNum from club" .
                " where account='" . $user["registerAccount"] . "'";
            $rs_cursor = $q_pdo->query($sql);
            $rs_cursor->setFetchMode(PDO::FETCH_ASSOC);
            $rs = $rs_cursor->fetchAll();
            if (!$rs || $rs[0]["accountNum"] <= 0) {
                $sql = "insert into club (clubname, area, principal, IDnumber, phone, age, sex, account, password) values("
                    .  "'" . $user["registerClubname"] . "','" . $user["registerArea"] . "', '" . $user["registerPrincipal"] . "'
                    , '" . $user["registerIDnumber"] . "','" . $user["registerPhone"] . "', '" . $user["registerAge"] . "'
                    , '" . $user["registerSex"] . "', '" . $user["registerAccount"] . "','". $userPwd."')" ;
                $iRsl = $q_pdo->exec($sql);

                $msg = "Register succeed";
                $status = 1;
                break;
            }
            else
            {
                $msg = "系统中存在相同的账号";
                break;
            }

        } while (false);
    } catch (Exception $errMsg) {
        $msg = $errMsg->getMessage();
        $status = $errMsg->getCode();
    }
    $rsl = array("status" => $status,"msg"=>$msg);
    echo json_encode($rsl);
});

$app->post("/user/login", function () {
    $msg = "具体问题请联系管理员";
    $status = 0;
    global $q_pdo;
    $userid = null;
    $username = null;
    try
    {
        $request = \Slim\Slim::getInstance()->request();
        $body = $request->getBody();
        $user = json_decode($body, true);
        $userPwd = hash("md5",$user["loginPwd"]);

        do {
            $sql = "select id from club" .
                " where account='" . $user["loginAccount"] .
                "' and password='" . $userPwd ."'";
            $rs_cursor = $q_pdo->query($sql);
            $rs_cursor->setFetchMode(PDO::FETCH_ASSOC);
            $rs = $rs_cursor->fetchAll();
            if (!$rs || count($rs) <= 0) {
                $msg = "账号或密码错误!!";
                break;
            }
            else
            {
                $userid = $rs[0]["id"];
                $status = 1;
            }

            $_SESSION['userLoggedin'] = $userid;
        } while (false);
    } catch (Exception $errMsg) {
        $msg = $errMsg->getMessage();
        $status = $errMsg->getCode();
    }
    $rsl = array("status" => $status,"msg"=>$msg ,"userid"=>$userid);
    echo json_encode($rsl);
});

$app->get("/getuser", function(){
    $msg = "";
    $status = 0;
    $clubname = "";$principal = "";
    global $q_pdo,$isUserLoggedIn;
    try{
        do{
            if(!$isUserLoggedIn){
                $msg = "Haven't login in";
                $status = -1;
                break;
            }
            $sql = "select clubname,principal from club
            where id = '" . $isUserLoggedIn . "'";
            $rs_cursor = $q_pdo->query($sql);
            $rs_cursor->setFetchMode(PDO::FETCH_ASSOC);
            $rs = $rs_cursor->fetchAll();
            if (!$rs || count($rs) <= 0) {
                $msg = "Fail to get clubname,principal by userid!!";
                break;
            }
            $clubname = $rs[0]["clubname"];
            $principal = $rs[0]["principal"];
            $status = 1;
            $msg = "Get userinfo succeed!!";
        }while(false);
    }
    catch(Exception $e){
        $msg = $e->getMessage();
        $status = $e->getCode();
    }
    $rsl = array("status" => $status,"msg"=>$msg, "clubname"=>$clubname,"principal"=>$principal);
    echo json_encode($rsl);
});

$app->post("/user/exit", function () {

    $msg = "";
    $status = 0;
    global  $isUserLoggedIn;
    do{
        if(!$isUserLoggedIn){
            $msg = "Haven't logon";
            $status = 1;
            break;
        }

        session_unset($_SESSION['userLoggedin']);
        session_destroy();
        $isUserLoggedIn = NULL;
        $status = 1;
        $msg = "Exit succeed";

    }while(false);
    $rsl = array("status" => $status,"msg"=>$msg);
    echo json_encode($rsl);
});

$app->run();

?>