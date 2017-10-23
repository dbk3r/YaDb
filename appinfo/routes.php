<?php

return [
    'routes' => [
	   ['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
     ['name' => 'disboDB#show', 'url' => '/showall/{id}', 'verb' => 'GET'],
     ['name' => 'disboDB#show_topic', 'url' => '/topic/{id}', 'verb' => 'GET'],
     ['name' => 'disboDB#NewTopicTemplate', 'url' => '/newtopic', 'verb' => 'GET'],
     ['name' => 'disboDB#create', 'url' => '/disboDB', 'verb' => 'POST'],
     ['name' => 'disboDB#update', 'url' => '/disboDB/{id}', 'verb' => 'PUT'],
     ['name' => 'disboDB#destroy', 'url' => '/disboDB/{id}', 'verb' => 'DELETE']
    ]
];
