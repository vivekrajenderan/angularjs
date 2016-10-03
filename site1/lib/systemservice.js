'use strict';

/**
 * @author PalMurugan C
 * 
 * @Description System Related Services are handling here
 */

myApp.service('SystemService',function(localStorageService){
    var _default = {
        serviceName : "services",
        authServiceName : "services"
    };

    /*
     * This function used to get ServiceBaseURL
     */
    this.getCurrentInstance = function(){
        return _default;
    };
   
});