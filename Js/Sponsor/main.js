function sponsorPageCtrl($scope, $resource) {

    var getService = $resource('../../Php/Sponsor/user.php/getuser');
    var exitService = $resource('../../Php/Sponsor/user.php/user/exit');

    $scope.showErrorMsg = false;

    var userdata = getService.get({}, function(){
        $scope.companyname = userdata.companyname;
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

angular.module('sponsorpage',['ngResource']);