# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added
- Dependency injector
- `tempo:attributes` command to save extra attributes for logging
- `tempo:list` command to list saved tempo attributes
- Save tempo group on log start
- Save tempo group on log stop
- `tempo:sync` to sync logs to Jira via Tempo

## [0.3.1] - 2020-04-23
### Fixed
- Fixed release checker

## [0.3.0] - 2020-04-22
### Added
- Timezone command
- Display logged time with `log:status`
- Display logged time with `log:sync`

## [0.2.2] - 2020-03-11
### Fixed
- Sync command with unauthorized response

## [0.2.1] - 2020-03-09
### Fixed
- Error on the composer create-project because of overcommit with no git repo

## [0.2.0] - 2020-03-08
### Added
- Check for updates on logs sync

## [0.1.0] - 2020-03-07
### Added
- Setup command
- Connect command
- Start command
- Stop command
- Status command
- Abort command
- Sync command
- Documentation
