<?php

return [

    'backup' => [

        /*
         * The name of this application. You can use this name to monitor
         * the backups.
         */
        'name' => env('APP_NAME', 'protjund-sistema'),

        'source' => [

            'files' => [

                /*
                 * The list of directories and files that will be included in the backup.
                 */
                'include' => [
                    base_path(),
                ],

                /*
                 * These directories and files will be excluded from the backup.
                 */
                'exclude' => [
                    base_path('vendor'),
                    base_path('node_modules'),
                ],

                /*
                 * Determines if symbols links should be followed.
                 */
                'follow_links' => false,

                /*
                 * Determines if it should ignore unreadable files.
                 */
                'ignore_unreadable_files' => false,

                /*
                 * This configuration allows you to add custom files to the backup.
                 */
                'relative_path' => null,
            ],

            /*
             * The names of the connections to the databases that should be backed up.
             * MySQL, PostgreSQL, SQLite and Mongo databases are supported.
             *
             * The app, academy and secrets are custom connections for this project.
             */
            'databases' => [
                'pgsql',
            ],
        ],

        /*
         * The database dump can be gzipped to decrease disk space usage.
         */
        'database_dump_compressor' => Spatie\DbDumper\Compressors\GzipCompressor::class,

        /*
         * The compression level for the database dump.
         * A value between 1 (low compression) and 9 (high compression).
         */
        'database_dump_compressor_level' => 6,

        'destination' => [

            /*
             * The filename prefix used for the backup zip file.
             */
            'filename_prefix' => '',

            /*
             * The disks on which the backups will be stored. Default is 's3'.
             */
            'disks' => [
                'local',
            ],
        ],

        'temporary_directory' => storage_path('app/backup-temp'),

        'password' => env('BACKUP_ARCHIVE_PASSWORD'),

        'encryption' => 'default',
    ],

    /*
     * You can get notified when specific events occur. Out of the box you can use 'mail'
     * and 'slack'. For Slack you need to install guzzlehttp/guzzle.
     *
     * You can also use your own notification classes, just make sure the class
     * extends Spatie\Backup\Notifications\Notifications\BackupHasFailedNotification
     */
    'notifications' => [

        'notifications' => [
            \Spatie\Backup\Notifications\Notifications\BackupHasFailedNotification::class => ['mail'],
            \Spatie\Backup\Notifications\Notifications\UnhealthyBackupWasFoundNotification::class => ['mail'],
            \Spatie\Backup\Notifications\Notifications\CleanupHasFailedNotification::class => ['mail'],
            \Spatie\Backup\Notifications\Notifications\BackupWasSuccessfulNotification::class => ['mail'],
            \Spatie\Backup\Notifications\Notifications\CleanupWasSuccessfulNotification::class => ['mail'],
        ],

        'notifiable' => \Spatie\Backup\Notifications\Notifiable::class,

        'mail' => [
            'to' => env('BACKUP_NOTIFICATION_EMAIL', 'admin@exemplo.com'),

            'from' => [
                'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
                'name' => env('MAIL_FROM_NAME', 'Example'),
            ],
        ],

        'slack' => [
            'webhook_url' => '',
            'channel' => null,
            'username' => null,
            'icon' => null,
        ],

        'discord' => [
            'webhook_url' => '',
            'username' => null,
            'avatar_url' => null,
        ],
    ],

    'monitor_backups' => [
        [
            'name' => env('APP_NAME', 'protjund-sistema'),
            'disks' => ['local'],
            'health_checks' => [
                \Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumAgeInDays::class => 1,
                \Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumStorageInMegabytes::class => 5000,
            ],
        ],
    ],

    'cleanup' => [
        'strategy' => \Spatie\Backup\Tasks\Cleanup\Strategies\DefaultStrategy::class,

        'default_strategy' => [

            'keep_all_backups_for_days' => 7,

            'keep_daily_backups_for_days' => 16,

            'keep_weekly_backups_for_weeks' => 8,

            'keep_monthly_backups_for_months' => 4,

            'keep_yearly_backups_for_years' => 2,

            'delete_oldest_backups_when_using_more_megabytes_than' => 5000,
        ],
    ],
];
