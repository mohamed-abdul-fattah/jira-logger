<?php

return [
    /**
     * Register commands for application
     */
    'commands' => [
        App\Commands\AbortCommand::class,
        App\Commands\Tempo\AttributesCommand::class,
        App\Commands\ConnectCommand::class,
        App\Commands\Tempo\ListCommand::class,
        App\Commands\SetupCommand::class,
        App\Commands\StartCommand::class,
        App\Commands\StatusCommand::class,
        App\Commands\StopCommand::class,
        App\Commands\SyncCommand::class,
        App\Commands\Tempo\SyncCommand::class,
        App\Commands\TimezoneCommand::class,
    ],

    /**
     * Mappings for dependencies abstractions and their concretions
     */
    'manifesto' => [
        App\Services\Connect\IConnect::class => App\Services\Connect\JiraConnect::class,
        App\Http\IRequestDispatcher::class => App\Http\Request::class,
        App\Http\IResponse::class => App\Http\Response::class,
        App\Repositories\Contracts\ITaskRepository::class => App\Repositories\TaskRepository::class,
    ]
];
