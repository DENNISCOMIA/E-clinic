<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
/**
 * ------------------------------------------------------------------
 * LavaLust - an opensource lightweight PHP MVC Framework
 * ------------------------------------------------------------------
 *
 * MIT License
 *
 * Copyright (c) 2020 Ronald M. Marasigan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package LavaLust
 * @author Ronald M. Marasigan <ronald.marasigan@yahoo.com>
 * @since Version 1
 * @link https://github.com/ronmarasigan/LavaLust
 * @license https://opensource.org/licenses/MIT MIT License
 */

/*
| -------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------
| Here is where you can register web routes for your application.
|
|
*/
// AUTH 
$router->get('/', 'AuthController::login');
$router->match('/auth/login', 'AuthController::login', ['GET', 'POST']);
$router->match('/auth/register', 'AuthController::register', ['GET', 'POST']);
$router->get('/auth/otp', 'AuthController::otp');  
$router->post('/auth/verify_otp', 'AuthController::verify_otp');
$router->get('/auth/logout', 'AuthController::logout');

// USER 
$router->get('/user/home', 'UserController::home');
$router->match('/user/profile', 'UserController::profile', ['GET', 'POST']);
$router->match('/user/appointment', 'UserController::appointment', ['GET', 'POST']);
$router->get('/user/history', 'UserController::history');

$router->post('/user/send_message', 'UserController::send_message');
$router->post('user/delete_history', 'UserController@delete_history');


// ADMIN 
$router->get('/admin/dashboard', 'AdminController::dashboard');
$router->match('/admin/appointments', 'AdminController::appointments', ['GET', 'POST']);
$router->match('/admin/manageAppointments', 'AdminController::manageAppointments', ['GET', 'POST']);

$router->match('/admin/findings', 'AdminController::findings', ['GET', 'POST']);
$router->get('/admin/records', 'AdminController::records');
$router->get('/admin/printRecords', 'AdminController::printRecords');