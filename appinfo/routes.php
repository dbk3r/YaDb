<?php

return [
    'routes' => [
	   ['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
     ['name' => 'disboDB#show', 'url' => '/showall/{id}', 'verb' => 'GET'],
     ['name' => 'disboDB#showTopic', 'url' => '/showtopic/{id}', 'verb' => 'GET'],
     ['name' => 'disboDB#NewReplyTopic', 'url' => '/newreplytopic/{id}', 'verb' => 'GET'],
     ['name' => 'disboDB#create', 'url' => '/disboDB', 'verb' => 'POST'],
     ['name' => 'disboDB#update', 'url' => '/disboDB/{id}', 'verb' => 'PUT'],
     ['name' => 'disboDB#destroy', 'url' => '/disboDB/{id}', 'verb' => 'DELETE']
    ]
];
