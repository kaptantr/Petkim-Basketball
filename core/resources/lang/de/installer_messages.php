<?php

return [

    /**
     *
     * Shared translations.
     *
     */
    'title' => 'Webtorn CMS Installer',
    'next' => 'Next Step',
    'back' => 'Zurück',
    'finish' => 'Install',
    'forms' => [
        'errorTitle' => 'Folgende Fehler sind aufgetreten:',
    ],

    /**
     *
     * Home page translations.
     *
     */
    'welcome' => [
        'templateTitle' => 'Willkommen',
        'title'   => 'Webtorn CMS Installer',
        'message' => 'Assistent für einfache Installation und Einrichtung.',
        'next'    => 'Check Requirements',
    ],

    /**
     *
     * Requirements page translations.
     *
     */
    'requirements' => [
        'templateTitle' => 'Schritt 1 | Serveranforderungen ',
        'title' => 'Serveranforderungen',
        'next'    => 'Berechtigungen prüfen',
    ],

    /**
     *
     * Permissions page translations.
     *
     */
    'permissions' => [
        'templateTitle' => 'Schritt 2 | Berechtigungen ',
        'title' => 'Berechtigungen',
        'next' => 'Umgebung konfigurieren',
    ],

    /**
     *
     * Environment page translations.
     *
     */
    'environment' => [
        'menu' => [
            'templateTitle' => 'Schritt 3 | Umgebungseinstellungen ',
            'title' => 'Umgebungseinstellungen',
            'desc' => 'Bitte wählen Sie aus, wie Sie die Apps-Datei <code> .env </ code> konfigurieren möchten.',
            'wizard-button' => 'Formularassistent einrichten',
            'classic-button' => 'Classic Text Editor',
        ],
        'wizard' => [
            'templateTitle' => 'Schritt 3 | Umgebungseinstellungen | Geführter Assistent ',
            'title' => 'Guided <code> .env </ code> Wizard',
            'tabs' => [
                'environment' => 'Umwelt',
                'database' => 'Datenbank',
                'application' => 'Application'
            ],
            'form' => [
                'name_required' => 'Ein Umgebungsname ist erforderlich.',
                'app_name_label' => 'App Name',
                'app_name_placeholder' => 'App Name',
                'app_environment_label' => 'App Environment',
                'app_environment_label_local' => 'Local',
                'app_environment_label_developement' => 'Development',
                'app_environment_label_qa' => 'Qa',
                'app_environment_label_production' => 'Produktion',
                'app_environment_label_other' => 'Andere',
                'app_environment_placeholder_other' => 'Geben Sie Ihre Umgebung ein ...',
                'app_debug_label' => 'App Debug',
                'app_debug_label_true' => 'True',
                'app_debug_label_false' => 'False',
                'app_log_level_label' => 'App Log Level',
                'app_log_level_label_debug' => 'debug',
                'app_log_level_label_info' => 'info',
                'app_log_level_label_notice' => 'Notice',
                'app_log_level_label_warning' => 'warning',
                'app_log_level_label_error' => 'error',
                'app_log_level_label_critical' => 'kritisch',
                'app_log_level_label_alert' => 'alert',
                'app_log_level_label_emergency' => 'Notfall',
                'app_url_label' => 'App Url',
                'app_url_placeholder' => 'App Url',
                'db_connection_label' => 'Datenbankverbindung',
                'db_connection_label_mysql' => 'mysql',
                'db_connection_label_sqlite' => 'sqlite',
                'db_connection_label_pgsql' => 'pgsql',
                'db_connection_label_sqlsrv' => 'sqlsrv',
                'db_host_label' => 'Datenbankhost',
                'db_host_placeholder' => 'Datenbankhost',
                'db_port_label' => 'Datenbankport',
                'db_port_placeholder' => 'Datenbankport',
                'db_name_label' => 'Datenbankname',
                'db_name_placeholder' => 'Datenbankname',
                'db_username_label' => 'Datenbankbenutzername',
                'db_username_placeholder' => 'Datenbankbenutzername',
                'db_password_label' => 'Datenbankkennwort',
                'db_password_placeholder' => 'Datenbankkennwort',

                'app_tabs' => [
                    'more_info' => 'Mehr Info',
                    'broadcasting_title' => 'Broadcasting, Caching, Session & amp; Warteschlange',
                    'broadcasting_label' => 'Broadcast Driver',
                    'broadcasting_placeholder' => 'Broadcast Driver',
                    'cache_label' => 'Cache-Treiber',
                    'cache_placeholder' => 'Cache-Treiber',
                    'session_label' => 'Sitzungstreiber',
                    'session_placeholder' => 'Sitzungstreiber',
                    'queue_label' => 'Queue Driver',
                    'queue_placeholder' => 'Queue Driver',
                    'redis_label' => 'Redis Driver',
                    'redis_host' => 'Redis Host',
                    'redis_password' => 'Redis Password',
                    'redis_port' => 'Redis Port',

                    'mail_label' => 'Mail ',
                    'mail_driver_label' => 'Mail Driver',
                    'mail_driver_placeholder' => 'Mail Driver',
                    'mail_host_label' => 'Mail Host',
                    'mail_host_placeholder' => 'Mail Host',
                    'mail_port_label' => 'Mail Port',
                    'mail_port_placeholder' => 'Mail Port',
                    'mail_username_label' => 'Mail Username',
                    'mail_username_placeholder' => 'Mail Username',
                    'mail_password_label' => 'Mail Password',
                    'mail_password_placeholder' => 'Mail Password',
                    'mail_encryption_label' => 'Mail Encryption',
                    'mail_encryption_placeholder' => 'Mail Encryption',

                    'pusher_label' => 'Pusher',
                    'pusher_app_id_label' => 'Pusher App Id',
                    'pusher_app_id_palceholder' => 'Pusher App Id',
                    'pusher_app_key_label' => 'Pusher App Key',
                    'pusher_app_key_palceholder' => 'Pusher App Key',
                    'pusher_app_secret_label' => 'Pusher App Secret',
                    'pusher_app_secret_palceholder' => 'Pusher App Secret',
                ],
                'buttons' => [
                    'setup_database' => 'Setup Database',
                    'setup_application' => 'Setup Application',
                    'install' => 'Install',
                ],
                'db_connection_failed' => 'Verbindung zur Datenbank fehlgeschlagen Überprüfen Sie Ihre Verbindungsdetails',
            ],
        ],
        'classic' => [
            'templateTitle' => 'Schritt 3 | Umgebungseinstellungen | Klassischer Editor ',
            'title' => 'Klassischer Umgebungseditor',
            'save' => 'Save .env',
            'back' => 'Formularassistent verwenden',
            'install' => 'Speichern und installieren',
        ],
        'success' => 'Ihre .env-Dateieinstellungen wurden gespeichert.',
        'errors' => 'Die .env-Datei kann nicht gespeichert werden. Bitte erstellen Sie sie manuell.',
    ],

    'install' => 'Install',

    /**
     *
     * Installed Log translations.
     *
     */
    'installed' => [
        'success_log_message' => 'Laravel Installer erfolgreich installiert',
    ],

    /**
     *
     * Final page translations.
     *
     */
    'final' => [
        'title' => 'Installation abgeschlossen',
        'templateTitle' => 'Installation abgeschlossen',
        'finished' => 'Webtorn wurde erfolgreich installiert.',
        'migration' => 'Migration &amp; Seed Console-Ausgabe Output: ',
        'console' => 'Application Console Output:',
        'log' => 'Installation Log Entry:',
        'env' => 'Final .env File:',
        'exit' => 'Gehe zum Webtorn Dashboard',
    ],

    /**
     *
     * Update specific translations
     *
     */
    'updater' => [
        /**
         *
         * Shared translations.
         *
         */
        'title' => 'Laravel Updater',

        /**
         *
         * Welcome page translations for update feature.
         *
         */
        'welcome' => [
            'title'   => 'Willkommen beim Updater',
            'message' => 'Willkommen beim Update-Assistenten.',
        ],

        /**
         *
         * Welcome page translations for update feature.
         *
         */
        'overview' => [
            'title'   => 'Übersicht',
            'message' => 'Es gibt 1 Update. | Es gibt: Nummernupdates.',
            'install_updates' => "Install Updates"
        ],

        /**
         *
         * Final page translations.
         *
         */
        'final' => [
            'title' => 'Fertig',
            'finished' => 'Die Datenbank der Anwendung wurde erfolgreich aktualisiert.',
            'exit' => 'Klicken Sie hier, um zum Admin-Dashboard zu gelangen',
        ],

        'log' => [
            'success_message' => 'Laravel Installer erfolgreich aktualisiert',
        ],
    ],

];
