<?php
/**
 * todos in order of importance:
 * 0. composer.json. MyApp is one project and Core is the framework. Both projects should be separated
 * 1. ajax security
 * 2. dependency injection -> symfony(ok i know i don't want to use frameworks)
 * 3. postEngine -> when we have dependency injection
 * 4. autoloader -> register namespaces
 * 5. extract viewModel concern from View
 * 6. handle subviews, property to say its a subview and not let app be rendered directly
 * just as a subcall
 * 7. posts and comments templates share a lot of functionalities, boths templates should extend
 * from a generic one
 * 8. model should be comunicated from backend to js.
 * 9. And on js there should be an mvc architecture. at the moment it's everything mixed on class
 * ajaxMaster. this is not good
 * 10. most browsers implement json_parse functionality. for the others i should use some library
 * to add this functionality
 * 11. use less for css
 * 12. avoid double submitting of addpost form
 * 13. email obfuscator
 * 14. validation errors near to the field with error, at the moment, all messages
 * error and success are beign displayed on the pot. having several comments, the user
 * will not see the messages.
 * 15. on every new comment i'm updating all the posts and comments. we should just update this comment
 *
 */
use Ffs\Ffs\Request\HttpRequest;
use Ffs\Ffs\Response\HttpResponse;
use Ffs\Ffs\Application\ApplicationFactory;
use Ffs\Ffs\Application\Config\WebConfig;
require '../src/Ffs/Autoloader.php';
try {
    $autoloader = new \Ffs\Autoloader();
    $responseHandler = new HttpResponse();
    $applicationFactory = new ApplicationFactory(
        new WebConfig(), $responseHandler, ApplicationFactory::VAL_WEB_ENVIRONMENT,
        $autoloader->getAppDir(), 'Ffs\Ffs', 'Ffs\MyApp', 'MyApp'
    );
    $myWebApplication = $applicationFactory->create();
    $myWebApplication->run(new HttpRequest());

} catch (Exception $e) {
    echo $e->getMessage();
}


