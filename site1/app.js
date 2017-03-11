var myApp = angular.module('myApp', [    
    'ui.router',
    'validation',
    'LocalStorageModule',
    'validation.rule',
    'login',
    'customers',
    'ui.load'
    ]);

myApp.config(function($stateProvider, $urlRouterProvider) {
     
     /*if (!localStorageService.get("userInfo")) {
        $urlRouterProvider.otherwise("/login");
    } else {
        $urlRouterProvider.otherwise("/dashboard");
    }*/
    
    $stateProvider       
        // HOME STATES AND NESTED VIEWS ========================================
        .state('dashboard', {
            url: '/dashboard',
            templateUrl: 'partials/dashboard.html'
            
        })
        .state('/', {
            url: '/login',
            templateUrl: 'login.html',
            controller: 'loginController'
        })
        // nested list with custom controller
        .state('login', {
            url: '/login',
            templateUrl: 'login.html',
            controller: 'loginController'
        })      
        .state('logout', {
            url: '/logout',
            template: "<h3>Logging out...</h3>",
            controller: 'logoutController'            
            
        })   
        .state('customers', {
            url: '/customers',
            templateUrl: 'partials/customers.html',
            controller: 'customerController'
        })
        .state('addcustomer', {
            url: '/addcustomer',
            templateUrl: 'partials/addcustomer.html',
            controller: 'customerController'
        })
        .state('users', {
            url: '/users',
            templateUrl: 'partials/users.html',
            controller: 'userController',
            resolve: {
            deps: ['uiLoad',
                function (uiLoad) {
                    return uiLoad.load(['lib/bootstrap-switch/bootstrap-switch.js']);
                }]
          }
        })   
        // ABOUT PAGE AND MULTIPLE NAMED VIEWS =================================
        .state('about', {
            url: '/about',
            views: {
                '': { templateUrl: 'partial-about.html' },
                'columnOne@about': { template: 'Look I am a column!' },
                'columnTwo@about': { 
                    templateUrl: 'table-data.html',
                    controller: 'scotchController'
                }
            }
            
        });
        
});

myApp.config(function($httpProvider, $urlRouterProvider) {
   
    $httpProvider.interceptors.push(function($injector, $location) {
        return {
            request: function(config) {
                console.log(config.url.indexOf('/'));  
                if (config.url.indexOf('/') === 0) {                    
                    // create instance for systemService 
                    var systemService = $injector.get("SystemService");
                    if (config.url.indexOf('login') > -1) {                        
                        config.url = systemService.getCurrentInstance().authServiceName + config.url; 
                                             
                    } else if (config.url.indexOf('rest/user/') > -1) {
                        config.url = systemService.getCurrentInstance().serviceName + config.url;  

                    }
                    else
                    {
                         config.url = systemService.getCurrentInstance().serviceName + config.url;
                         console.log(config.url);
                    }
                    
                }

                return config;
            }
        }
    });
});


myApp.run(function ($rootScope, $state,$location,localStorageService) {
    $rootScope.$state = $state; //Get state info in view
  
    if (localStorageService.get("userInfo")) {           
            $rootScope.userInfo = localStorageService.get("userInfo");
            //$location.path("/dashboard");
        }
        else
        {           
            $location.path("/login");

        } 
});

