<?php
return [
    'jobrouter:crypt:generatekey' => [
        'class' => \Brotkrueml\JobRouterConnector\Command\GenerateKeyCommand::class,
        'schedulable' => false,
    ],
];