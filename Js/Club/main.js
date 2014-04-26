function clubPageCtrl($scope, $resource) {

    var getService = $resource('../../Php/Club/user.php/getuser');
    var exitService = $resource('../../Php/Club/user.php/user/exit');

    $scope.showErrorMsg = false;

    var userdata = getService.get({}, function(){
        $scope.clubname = userdata.clubname;
        $scope.principal = userdata.principal;
        if(userdata.status == -1)
        {
            window.location.replace("login.html");
        }
    });

    $scope.exit = function(){
        var data = exitService.save({},function(){
            if(data.status == 1)
            {
                window.location.replace("login.html");
            }
        });
    }

}

angular.module('clubpage',['ngResource']);