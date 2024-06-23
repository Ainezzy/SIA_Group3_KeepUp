<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */
    

    /*This section sets the default authentication "guard" and password reset options.
    These defaults can be adjusted as necessary but provide a good starting point for 
    most applications.*/
    
    'defaults' => [
        'guard' => env('AUTH_GUARD', 'api'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "token"
    |
    */


    /*This section allows you to define every authentication guard for your application. 
    A default configuration is provided using session storage and the Eloquent user provider
    , with support for "token" driver.*/
    
    'guards' => [
        'api' => ['driver' => 'passport'],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    /*This section defines how users are retrieved from your database or other
    storage mechanisms for authentication. If you have multiple user tables or models, 
    you can configure multiple sources and assign them to different authentication guards.*/

    'providers' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Here you may set the options for resetting passwords including the view
    | that is your password reset e-mail. You may also set the name of the
    | table that maintains all of the reset tokens for your application.
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */


    /*This section sets the options for resetting passwords, including the view for 
    the password reset e-mail and the name of the table that maintains reset tokens. 
    You can specify multiple password reset configurations if you have more than one 
    user table or model, with a token expiration time to enhance security.*/
    
    'passwords' => [
        //
    ],

];
