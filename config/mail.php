<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mail Driver
    |--------------------------------------------------------------------------
    |
    | Laravel supports both SMTP and PHP's "mail" function as drivers for the
    | sending of e-mail. You may specify which one you're using throughout
    | your application here. By default, Laravel is setup for SMTP mail.
    |
    | Supported: "smtp", "sendmail", "mailgun", "mandrill", "ses",
    |            "sparkpost", "log", "array"
    |
    */

    "driver" => env("MAIL_DRIVER", "smtp"),

    /*
    |--------------------------------------------------------------------------
    | SMTP Host Address
    |--------------------------------------------------------------------------
    |
    | Here you may provide the host address of the SMTP server used by your
    | applications. A default option is provided that is compatible with
    | the Mailgun mail service which will provide reliable deliveries.
    |
    */

    "host" => env("MAIL_HOST", "smtp.mailgun.org"),

    /*
    |--------------------------------------------------------------------------
    | SMTP Host Port
    |--------------------------------------------------------------------------
    |
    | This is the SMTP port used by your application to deliver e-mails to
    | users of the application. Like the host we have set this value to
    | stay compatible with the Mailgun e-mail application by default.
    |
    */

    "port" => env("MAIL_PORT", 587),

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | You may wish for all e-mails sent by your application to be sent from
    | the same address. Here, you may specify a name and address that is
    | used globally for all e-mails that are sent by your application.
    |
    */

    "from" => [
        "address" => env("MAIL_FROM_ADDRESS", "hello@example.com"),
        "name" => env("MAIL_FROM_NAME", "Example"),
    ],

    /*
    |--------------------------------------------------------------------------
    | E-Mail Encryption Protocol
    |--------------------------------------------------------------------------
    |
    | Here you may specify the encryption protocol that should be used when
    | the application send e-mail messages. A sensible default using the
    | transport layer security protocol should provide great security.
    |
    */

    "encryption" => env("MAIL_ENCRYPTION", "tls"),

    /*
    |--------------------------------------------------------------------------
    | SMTP Server Username
    |--------------------------------------------------------------------------
    |
    | If your SMTP server requires a username for authentication, you should
    | set it here. This will get used to authenticate with your server on
    | connection. You may also set the "password" value below this one.
    |
    */

    "username" => env("MAIL_USERNAME"),

    "password" => env("MAIL_PASSWORD"),

    /*
    |--------------------------------------------------------------------------
    | Sendmail System Path
    |--------------------------------------------------------------------------
    |
    | When using the "sendmail" driver to send e-mails, we will need to know
    | the path to where Sendmail lives on this server. A default path has
    | been provided here, which will work well on most of your systems.
    |
    */

    "sendmail" => "/usr/sbin/sendmail -bs",

    /*
    |--------------------------------------------------------------------------
    | Markdown Mail Settings
    |--------------------------------------------------------------------------
    |
    | If you are using Markdown based email rendering, you may configure your
    | theme and component paths here, allowing you to customize the design
    | of the emails. Or, you may simply stick with the Laravel defaults!
    |
    */

    "markdown" => [
        "theme" => "default",

        "paths" => [
            resource_path("views/vendor/mail"),
        ],
    ],

    /*
    |---------------------------------------------------------------------------------------------------------------
    | SSL/TLS management configuration for Swift Mailer, in order to avoid below error
    |
    | @see https://stackoverflow.com/questions/26896265/php-swiftmailer-using-starttls-and-self-signed-certificates#41267848
    |---------------------------------------------------------------------------------------------------------------
    |
    | [2020-07-24 10:49:33] production.ERROR:
    |                         stream_socket_enable_crypto():
    |                           SSL operation failed with code 1.
    |                             OpenSSL Error messages:
    |                               error:14090086:SSL routines:ssl3_get_server_certificate:certificate verify failed
    |                                 {"exception":"[object] (ErrorException(code: 0): stream_socket_enable_crypto(): SSL operation failed with code 1. OpenSSL Error messages:
    |                               error:14090086:SSL routines:ssl3_get_server_certificate:certificate verify failed
    |                                 at /var/www/clqei/novara/vendor/swiftmailer/swiftmailer/lib/classes/Swift/Transport/StreamBuffer.php:94)
    | [stacktrace]
    | #0 [internal function]: Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, 'stream_socket_e...', '/var/www/clqei/...', 94, Array)
    | #1 /var/www/clqei/novara/vendor/swiftmailer/swiftmailer/lib/classes/Swift/Transport/StreamBuffer.php(94): stream_socket_enable_crypto(Resource id #641, true, 57)
    | #2 /var/www/clqei/novara/vendor/swiftmailer/swiftmailer/lib/classes/Swift/Transport/EsmtpTransport.php(348): Swift_Transport_StreamBuffer->startTLS()
    | #3 /var/www/clqei/novara/vendor/swiftmailer/swiftmailer/lib/classes/Swift/Transport/AbstractSmtpTransport.php(148): Swift_Transport_EsmtpTransport->doHeloCommand()
    | #4 /var/www/clqei/novara/vendor/swiftmailer/swiftmailer/lib/classes/Swift/Mailer.php(65): Swift_Transport_AbstractSmtpTransport->start()
    | #5 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(451): Swift_Mailer->send(Object(Swift_Message), Array)
    | #6 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Mail/Mailer.php(235): Illuminate\\Mail\\Mailer->sendSwiftMessage(Object(Swift_Message))
    | #7 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Notifications/Channels/MailChannel.php(64): Illuminate\\Mail\\Mailer->send(Object(Illuminate\\Support\\HtmlString), Array, Object(Closure))
    | #8 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Notifications/NotificationSender.php(113): Illuminate\\Notifications\\Channels\\MailChannel->send(Object(App\\User), Object(App\\Notifications\\ResetPassword))
    | #9 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Notifications/NotificationSender.php(89): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\User), '40cf2a7f-ccae-4...', Object(App\\Notifications\\ResetPassword), 'mail')
    | #10 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Notifications/NotificationSender.php(64): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\ResetPassword))
    | #11 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Notifications/ChannelManager.php(35): Illuminate\\Notifications\\NotificationSender->send(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\ResetPassword))
    | #12 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Notifications/RoutesNotifications.php(18): Illuminate\\Notifications\\ChannelManager->send(Object(App\\User), Object(App\\Notifications\\ResetPassword))
    | #13 /var/www/clqei/novara/app/User.php(136): App\\User->notify(Object(App\\Notifications\\ResetPassword))
    | #14 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Auth/Passwords/PasswordBroker.php(70): App\\User->sendPasswordResetNotification('1a8d46bb2f97b67...')
    | #15 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Foundation/Auth/SendsPasswordResetEmails.php(34): Illuminate\\Auth\\Passwords\\PasswordBroker->sendResetLink(Array)
    | #16 [internal function]: App\\Http\\Controllers\\Auth\\ForgotPasswordController->sendResetLinkEmail(Object(Illuminate\\Http\\Request))
    | #17 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Routing/Controller.php(54): call_user_func_array(Array, Array)
    | #18 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php(45): Illuminate\\Routing\\Controller->callAction('sendResetLinkEm...', Array)
    | #19 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Routing/Route.php(212): Illuminate\\Routing\\ControllerDispatcher->dispatch(Object(Illuminate\\Routing\\Route), Object(App\\Http\\Controllers\\Auth\\ForgotPasswordController), 'sendResetLinkEm...')
    | #20 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Routing/Route.php(169): Illuminate\\Routing\\Route->runController()
    | #21 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Routing/Router.php(658): Illuminate\\Routing\\Route->run()
    | #22 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(30): Illuminate\\Routing\\Router->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
    | #23 /var/www/clqei/novara/app/Http/Middleware/RedirectIfAuthenticated.php(24): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
    | #24 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(149): App\\Http\\Middleware\\RedirectIfAuthenticated->handle(Object(Illuminate\\Http\\Request), Object(Closure))
    | #25 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
    | #26 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Routing/Middleware/SubstituteBindings.php(41): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
    | #27 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(149): Illuminate\\Routing\\Middleware\\SubstituteBindings->handle(Object(Illuminate\\Http\\Request), Object(Closure))
    | #28 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
    | #29 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/VerifyCsrfToken.php(68): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
    | #30 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(149): Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken->handle(Object(Illuminate\\Http\\Request), Object(Closure))
    | #31 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
    | #32 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/View/Middleware/ShareErrorsFromSession.php(49): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
    | #33 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(149): Illuminate\\View\\Middleware\\ShareErrorsFromSession->handle(Object(Illuminate\\Http\\Request), Object(Closure))
    | #34 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
    | #35 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php(63): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
    | #36 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(149): Illuminate\\Session\\Middleware\\StartSession->handle(Object(Illuminate\\Http\\Request), Object(Closure))
    | #37 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
    | #38 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Cookie/Middleware/AddQueuedCookiesToResponse.php(37): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
    | #39 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(149): Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse->handle(Object(Illuminate\\Http\\Request), Object(Closure))
    | #40 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
    | #41 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Cookie/Middleware/EncryptCookies.php(66): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
    | #42 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(149): Illuminate\\Cookie\\Middleware\\EncryptCookies->handle(Object(Illuminate\\Http\\Request), Object(Closure))
    | #43 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
    | #44 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(102): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
    | #45 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Routing/Router.php(660): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
    | #46 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Routing/Router.php(635): Illuminate\\Routing\\Router->runRouteWithinStack(Object(Illuminate\\Routing\\Route), Object(Illuminate\\Http\\Request))
    | #47 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Routing/Router.php(601): Illuminate\\Routing\\Router->runRoute(Object(Illuminate\\Http\\Request), Object(Illuminate\\Routing\\Route))
    | #48 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Routing/Router.php(590): Illuminate\\Routing\\Router->dispatchToRoute(Object(Illuminate\\Http\\Request))
    | #49 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(176): Illuminate\\Routing\\Router->dispatch(Object(Illuminate\\Http\\Request))
    | #50 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(30): Illuminate\\Foundation\\Http\\Kernel->Illuminate\\Foundation\\Http\\{closure}(Object(Illuminate\\Http\\Request))
    | #51 /var/www/clqei/novara/vendor/barryvdh/laravel-debugbar/src/Middleware/InjectDebugbar.php(58): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
    | #52 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(149): Barryvdh\\Debugbar\\Middleware\\InjectDebugbar->handle(Object(Illuminate\\Http\\Request), Object(Closure))
    | #53 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
    | #54 /var/www/clqei/novara/vendor/fideloper/proxy/src/TrustProxies.php(56): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
    | #55 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(149): Fideloper\\Proxy\\TrustProxies->handle(Object(Illuminate\\Http\\Request), Object(Closure))
    | #56 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
    | #57 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php(30): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
    | #58 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(149): Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest->handle(Object(Illuminate\\Http\\Request), Object(Closure))
    | #59 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
    | #60 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php(30): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
    | #61 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(149): Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest->handle(Object(Illuminate\\Http\\Request), Object(Closure))
    | #62 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
    | #63 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/ValidatePostSize.php(27): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
    | #64 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(149): Illuminate\\Foundation\\Http\\Middleware\\ValidatePostSize->handle(Object(Illuminate\\Http\\Request), Object(Closure))
    | #65 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
    | #66 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/CheckForMaintenanceMode.php(46): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
    | #67 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(149): Illuminate\\Foundation\\Http\\Middleware\\CheckForMaintenanceMode->handle(Object(Illuminate\\Http\\Request), Object(Closure))
    | #68 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php(53): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Http\\Request))
    | #69 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(102): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))
    | #70 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(151): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))
    | #71 /var/www/clqei/novara/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(116): Illuminate\\Foundation\\Http\\Kernel->sendRequestThroughRouter(Object(Illuminate\\Http\\Request))
    | #72 /var/www/clqei/novara/public/index.php(55): Illuminate\\Foundation\\Http\\Kernel->handle(Object(Illuminate\\Http\\Request))
    |
    */

    "stream" => [
        "ssl" => [
            "allow_self_signed" => true,
            "verify_peer" => false,
            "verify_peer_name" => false,
        ],
    ],

];
