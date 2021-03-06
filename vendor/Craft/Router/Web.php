<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Router;

class Web extends Basic
{

    /** @var array */
    protected $verbs = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'HEAD'];


    /**
     * Find route
     * @param string $query
     * @param array $customs
     * @param mixed $fallback
     * @return Route
     */
    public function find($query, array $customs = [], $fallback = false)
    {
        // prepare query
        list($query, $customs) = $this->prepare($query, $customs);

        // search in all routes
        foreach($this->routes as $route)
        {
            // route filter
            $route = $this->filter($route);

            // compile pattern
            $pattern = $this->compile($route->from);

            // compare
            if(preg_match($pattern, $query, $out)){

                // check customs
                if(!$this->check($route, $customs)) {
                    continue;
                }

                // parse data
                unset($out[0]);
                $route->data = $this->parse($out);

                return $route;
            }
        }

        return $fallback;
    }


    /**
     * Find route
     * @param string $query
     * @param array $customs
     * @return string
     */
    protected function prepare($query, array $customs = [])
    {
        // resolve path
        list($verb, $query) = $this->resolve($query);

        // update customs
        if($verb) {
            $customs['method'] = $verb;
        }

        // clean query
        $query = '/' . trim($query, '/');

        return [$query, $customs];
    }


    /**
     * Filter route
     * @param Route $route
     * @return Route
     */
    protected function filter(Route $route)
    {
        // resolve path
        list($verb, $query) = $this->resolve($route->from);

        // update customs
        if($verb) {
            $route->customs['method'] = $verb;
        }

        // clean query
        $route->from =  '/' . trim($query, '/');

        return $route;
    }


    /**
     * Compile path into regex
     * @param $path
     * @return mixed|string
     */
    protected function compile($path)
    {
        $pattern = str_replace('/', '\/', $path);
        $pattern = preg_replace('#\:(\w+)#', '(?P<arg__$1>(.+))', $pattern);
        $pattern = preg_replace('#\+(\w+)#', '(?P<env__$1>(.+))', $pattern);
        $pattern = '#^' . $pattern . '$#';

        return $pattern;
    }


    /**
     * Check customs
     * @param Route $route
     * @param array $customs
     * @return bool
     */
    protected function check(Route $route, array $customs = [])
    {
        return (array_intersect_assoc($customs, $route->customs) == $route->customs);
    }


    /**
     * Parse results
     * @param array $results
     * @return array
     */
    protected function parse(array $results)
    {
        // default values
        $data = [
            'args' => [],
            'envs' => []
        ];

        // parse
        foreach($results as $key => $value) {
            if(substr($key, 0, 5) == 'arg__' or substr($key, 0, 5) == 'env__') {
                $group = substr($key, 0, 3) . 's';
                $label = substr($key, 5);
                $data[$group][$label] = $value;
            }
        }

        return $data;
    }


    /**
     * Resolve query
     * @param $query
     * @return array
     */
    protected function resolve($query)
    {
        // has http verb
        if($query and strpos(' ', $query) !== false) {

            // get verb
            list($verb, $path) = explode(' ', $query);
            $verb = strtoupper($verb);

            // return resolved
            if(in_array($verb, $this->verbs)) {
                return [$verb, $path];
            }

        }

        return [null, $query];
    }


}