function clubCtrl($scope, $resource) {

    var loginService = $resource('../../Php/Club/user.php/user/login');
    var registerService = $resource('../../Php/Club/user.php/user/register');

    $scope.isLoginError = false;
    $scope.loginSucceed = false;
    $scope.isRegisterError = false;
    $scope.registerSucceed = false;

    $scope.sex = "男";

    $scope.userRegister = function () {

        try {

            var requestParams = {
                registerAccount:$scope.account,
                registerPwd:$scope.password,
                registerClubname:$scope.clubname,
                registerArea:$scope.area,
                registerPrincipal:$scope.principal,
                registerIDnumber:$scope.IDnumber,
                registerPhone:$scope.phone,
                registerAge:$scope.selected,
                registerSex:$scope.sex
            };

            var registerUser = registerService.save({}, requestParams,function(){

                if(registerUser.status == 1)
                {
                    $scope.registerSucceed = true;
                    window.location.replace("login.html");
                    return;
                }

                $scope.isRegisterError = true;
                $scope.registerErrorMsg = registerUser.msg;

                $scope.actionStatusMsg="注册失败";
            });
        }
        catch (arg_err) {
            $scope.isRegisterError = true;
            $scope.registerErrorMsg=arg_err;
            $scope.actionStatusMsg="注册失败";
        }
    }

    $scope.userLogin = function () {

        try {

            var requestParams = {
                loginAccount:$scope.account,
                loginPwd:$scope.password
            };

            var loginUser = loginService.save({}, requestParams,function(){

                if(loginUser.status == 1)
                {
                    $scope.loginSucceed = true;
                    window.location.replace("page.html");
                    return;
                }

                $scope.isLoginError = true;
                $scope.loginErrorMsg = loginUser.msg;

                $scope.actionStatusMsg="登陆失败";
            });
        }
        catch (arg_err) {
            $scope.isLoginError = true;
            $scope.actionStatusMsg="登陆失败";
            $scope.loginErrorMsg=arg_err;
        }
    }

}

angular.module('clubsign',['ngResource']);