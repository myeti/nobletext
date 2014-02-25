<?php

/**
 * This is a controller, a class that contains many actions.
 * Use it to write all your business layer !
 */
namespace My\Logic;

use Craft\Box\Auth;
use Craft\Box\Flash;

class Front
{


    /**
     * Attempt login
     * @render views/auth.login
     * @return mixed
     */
    public function login()
    {
        // login submitted
        if(post())
        {
            // extract env
            $username = post('username');
            $password = post('password');

            // login
            if($username == NB_USERNAME and sha1($password) == NB_PASSWORD) {
                Auth::login();
                go('/');
            }
            else {
                Flash::set('login.fail', 'Bad user data.');
            }
        }
    }


    /**
     * Log user out
     */
    public function logout()
    {
        Auth::logout();
        go('/');
    }

}