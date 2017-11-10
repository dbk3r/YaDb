<?php

return [
    'routes' => [
	   ['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
     ['name' => 'disboDB#showall', 'url' => '/showall/{id}', 'verb' => 'GET'],
     ['name' => 'disboDB#showTopic', 'url' => '/showtopic/{uuid}', 'verb' => 'GET'],
     ['name' => 'disboDB#TopicFormHeader', 'url' => '/topicformheader/{id}', 'verb' => 'GET'],
     ['name' => 'disboDB#TopicContent', 'url' => '/topiccontent/{id}', 'verb' => 'GET'],
     ['name' => 'disboDB#createTopic', 'url' => '/newtopic', 'verb' => 'POST'],
     ['name' => 'disboDB#update', 'url' => '/disboDB/{id}', 'verb' => 'PUT'],
     ['name' => 'disboDB#destroy', 'url' => '/disboDB/{id}', 'verb' => 'DELETE']
    ]
];
