'use strict';
angular.module('login', []);

//Html tag convert
myApp.filter('to_trusted', ['$sce', function ($sce) {
        return function (text) {
            return $sce.trustAsHtml(text);
        };
    }]);
//Factories
myApp.factory('userServices', ['$http', function ($http) {

        var factoryDefinitions = {
            login: function (loginReq) {
                var promise = $http({
                    url: '/ajax-login',
                    method: "POST",
                    headers: {'Content-Type': 'application/json'},
                    data: loginReq
                }).success(function (data) {
                    return data;
                }).error(function (data) {
                    return data;
                });
                return promise;
            },
            signup: function (signupReq) {
                return $http.post('partials/common/mock/success.json', signupReq).success(function (data) {
                    return data;
                });
            }
        }

        return factoryDefinitions;
    }
]);

//Controllers
myApp.controller('loginController', ['$scope', 'userServices', '$location', '$rootScope', '$timeout', 'localStorageService', function ($scope, userServices, $location, $rootScope, $timeout, localStorageService) {
        $scope.errmessage = '';
        //$scope.login = {email: "admin@aadhar.com", password: "admin123"};
        if (localStorageService.get("userInfo")) {
            $rootScope.userInfo = localStorageService.get("userInfo");
            $location.path("/dashboard");
        }
        else
        {
            $location.path("/login");

        }
        $scope.doLogin = function () {
            if ($scope.loginForm.$valid) {
                userServices.login($scope.login, $rootScope).then(function (result) {
                    if (result.data.status == 1) {
                        var result_data = JSON.stringify(result.data.msg);
                        var session_data = JSON.parse(result_data);

                        $rootScope["userInfo"] = session_data;
                        localStorageService.set("userInfo", session_data);

                        $location.path("/dashboard");
                    }
                    else
                    {
                        $scope.errmessage = result.data.msg;
                        $timeout(function () {
                            $scope.errmessage = '';
                        }, 5000);
                    }
                });
            }
        };
    }]);

myApp.controller('logoutController', ['$scope', '$location', '$rootScope', 'localStorageService', function ($scope, $location, $rootScope, localStorageService) {
        sessionStorage.clear();
        localStorageService.remove('userInfo');
        $rootScope.userInfo = false;
        $location.path("/login");
    }]);