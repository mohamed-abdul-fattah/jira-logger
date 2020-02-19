![Overview](jiralogger.png)
## Table of Content
* Installation
    * Regular
    * Docker
* [Usage](#usage)
    * [Setup](#setup-command)
    * [Connect](#connect-command)
    * [Start](#start-command)
    * [Stop](#stop-command)
    * [Status](#status-command)
    * [Abort](#abort-command)
    * [Sync](#sync-command)
* Testing
* Contributing
* ChangeLog

## Usage
### Setup Command
`setup` command should be run once at the setup of the command line tool 
to create the database and request for your Jira server URI.
```bash
php jiralogger setup https://jira.com/
```

### Connect Command
`connect` command is your way for authentication with your Jira server. 
Authentication is needed for logs syncing process.
```bash
# This way, connec command will ask for your Jira username and password
php jiralogger connect

# You can provide username via command options
php jiralogger -u john.doe
```

### Start Command
Use `log:start` command to start a logging timer for a Jira task.
```bash
# Running start with only task would start with "now" time
php jiralogger log:start TASK-123

# Optionally, you can provide log starting time and log description
php jiralogger log:start TASK-123 -t 13:20 -d "Work in progress"
```

### Stop Command
Use `log:stop` command to stop a logging timer for a Jira task.
```bash
# Running stop will stop task at "now" time
php jiralogger log:stop

# Optionally, you can provide end time and override starting description
php jiralogger log:stop -t 15:30 -d "DONE"
```

### Status Command
Use `log:status` command to get the current running task information if any,
and the total un-synced logs items.
```bash
php jiralogger log:status
```

### Abort Command
Use `log:abort` command to abort a logging timer for a started Jira task.
```bash
php jiralogger log:abort
```

### Sync Command
Use `log:sync` command to sync and log times to Jira tasks. 
Requires authentication (via `connect` command)
```bash
php jiralogger log:sync
```
