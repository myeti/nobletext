<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Web;

use Craft\Box\Env;
use Craft\Reflect\Injector;
use Craft\Reflect\Resolver;
use Craft\Router\Matcher;
use Craft\Router\Web as WebRouter;

class Kernel implements Handler
{

    /** @var Handler */
    protected $handler;

    /** @var Matcher */
    protected $router;


    /**
     * Setup kernel
     * @param Handler $handler
     * @param Matcher $router
     */
    public function __construct(Handler $handler, Matcher $router)
    {
        $this->handler = $handler;
        $this->router = $router;
    }


    /**
     * Handle context request
     * @param Request $request
     * @throws Event\NotFound
     * @return Response
     */
    public function handle(Request $request)
    {
        // route query
        $route = $this->router->find($request->query);

        // 404
        if(!$route) {
            throw new Event\NotFound('Route "' . $request->query . '" not found.');
        }

        // special case : web router
        if($this->router instanceof WebRouter) {

            // env
            foreach($route->data['envs'] as $key => $value) {
                Env::set($key, $value);
            }

            // args
            $route->data = $route->data['args'];

        }

        // update request
        $request->action = $route->to;
        $request->args = $route->data;

        // run dispatcher
        return $this->handler->handle($request);
    }


    /**
     * Forge app from routes
     * @param array $routes
     * @param Injector $injector
     * @param Firewall\Strategy $strategy
     * @return App
     */
    public static function forge(array $routes, Injector $injector = null, Firewall\Strategy $strategy = null)
    {
        // create resolver
        $resolver = new Resolver($injector);

        // create firewall
        $firewall = $strategy ?: new Firewall\RankStrategy();

        // create dispatcher
        $dispatcher = new Dispatcher($resolver, $firewall);

        // create router
        $router = new WebRouter($routes);

        // create kernel
        return new self($dispatcher, $router);
    }

}