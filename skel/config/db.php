<?php

$db = array(
    'dsn'      => 'mysql:host=localhost;dbname=test',
    'username' => '****',
    'password' => '****',
);

// if use Idiorm and Paris.
// ORM::configure($db['dsn']);
// ORM::configure('username', $db['username']);
// ORM::configure('password', $db['password']);

return $db; // Don't delete this line

