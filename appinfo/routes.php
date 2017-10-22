<?php

return [
    'routes' => [
	   ['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
     ['name' => 'disboDB#show', 'url' => '/disboDB/{id}', 'verb' => 'GET'],
     ['name' => 'disboDB#show_topic', 'url' => '/disboDB/topic/{id}', 'verb' => 'GET'],
     ['name' => 'disboDB#create', 'url' => '/disboDB', 'verb' => 'POST'],
     ['name' => 'disboDB#update', 'url' => '/disboDB/{id}', 'verb' => 'PUT'],
     ['name' => 'disboDB#destroy', 'url' => '/disboDB/{id}', 'verb' => 'DELETE']
    ]
];
