<?php

return [
    /**
     * Register commands for application
     */
    'commands' => [
        \App\Commands\AbortCommand::class,
        \App\Commands\ConnectCommand::class,
        \App\Commands\SetupCommand::class,
        \App\Commands\StartCommand::class,
        \App\Commands\StatusCommand::class,
        \App\Commands\StopCommand::class,
        \App\Commands\SyncCommand::class,
        \App\Commands\TimezoneCommand::class,
    ],
];
