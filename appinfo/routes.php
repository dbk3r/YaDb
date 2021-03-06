<?php

return [
    'routes' => [
	   ['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
     ['name' => 'disboDB#showall', 'url' => '/showall', 'verb' => 'POST'],
     ['name' => 'disboDB#showTopic', 'url' => '/showtopic/{uuid}', 'verb' => 'GET'],
     ['name' => 'disboDB#TopicFormHeader', 'url' => '/topicformheader/{id}', 'verb' => 'GET'],
     ['name' => 'disboDB#TopicContent', 'url' => '/topiccontent/{id}', 'verb' => 'GET'],
     ['name' => 'disboDB#createTopic', 'url' => '/newtopic', 'verb' => 'POST'],
     ['name' => 'disboDB#saveTopic', 'url' => '/savetopic', 'verb' => 'POST'],
     ['name' => 'disboDB#pinTopic', 'url' => '/pintopic', 'verb' => 'POST'],
     ['name' => 'disboDB#replyTopic', 'url' => '/replytopic', 'verb' => 'POST'],
     ['name' => 'disboDB#deleteTopic', 'url' => '/deletetopic/{id}', 'verb' => 'GET']
    ]
];
