<?php

return [

    /**
     *
     * Shared translations.
     *
     */
    'title' => 'Webtorn CMS Installer',
    'next' => 'Sonraki adım',
    'back' => 'Önceki',
    'finish' => 'Kurulum',
    'forms' => [
        'errorTitle' => 'Şu hatalar oluştu:',
    ],

    /**
     *
     * Home page translations.
     *
     */
    'welcome' => [
        'templateTitle' => 'Hoş Geldiniz',
        'title'   => 'Webtorn CMS Installer',
        'message' => 'Kolay Kurulum ve Kurulum Sihirbazı.',
        'next'    => 'Gereksinimleri Kontrol Et',
    ],

    /**
     *
     * Requirements page translations.
     *
     */
    'requirements' => [
        'templateTitle' => 'Adım 1 | Server Gereksinimleri',
        'title' => 'Server Gereksinimleri',
        'next'    => 'Gereksinimleri Kontrol Et',
    ],

    /**
     *
     * Permissions page translations.
     *
     */
    'permissions' => [
        'templateTitle' => 'Adım 2 | Yetkiler',
        'title' => 'Yetkiler',
        'next' => 'Ortamı Yapılandır',
    ],

    /**
     *
     * Environment page translations.
     *
     */
    'environment' => [
        'menu' => [
            'templateTitle' => 'Adım 3 | Ortam Ayarları',
            'title' => 'Ortam Ayarları',
            'desc' => 'Lütfen apps klasöründeki <code>.env</code> dosyasını nasıl yapılandırmak istediğinizi seçin.',
            'wizard-button' => 'Form Sihirbazı Kurulumu',
            'classic-button' => 'Klasik Metin Editörü',
        ],
        'wizard' => [
            'templateTitle' => 'Adım 3 | Ortam Ayarları | Kılavuz Sihirbazı',
            'title' => '<code>.env</code> dosyası Kılavuz Sihirbazı',
            'tabs' => [
                'environment' => 'Ortam',
                'database' => 'Veritabanı',
                'application' => 'Uygulama'
            ],
            'form' => [
                'name_required' => 'Bir ortam adı gerekli.',
                'app_name_label' => 'Uygulama Adı',
                'app_name_placeholder' => 'Uygulama Adı',
                'app_environment_label' => 'Uygulama Ortamı',
                'app_environment_label_local' => 'Yerel',
                'app_environment_label_developement' => 'Geliştirme',
                'app_environment_label_qa' => 'SsS',
                'app_environment_label_production' => 'Üretim',
                'app_environment_label_other' => 'Diğer',
                'app_environment_placeholder_other' => 'Ortam gir...',
                'app_debug_label' => 'Uygulama Hata Ayıklama',
                'app_debug_label_true' => 'Doğru',
                'app_debug_label_false' => 'Yanlış',
                'app_log_level_label' => 'Uygulama Log Seviyesi',
                'app_log_level_label_debug' => 'debug',
                'app_log_level_label_info' => 'info',
                'app_log_level_label_notice' => 'notice',
                'app_log_level_label_warning' => 'warning',
                'app_log_level_label_error' => 'error',
                'app_log_level_label_critical' => 'critical',
                'app_log_level_label_alert' => 'alert',
                'app_log_level_label_emergency' => 'emergency',
                'app_url_label' => 'Uygulama Link',
                'app_url_placeholder' => 'Uygulama Link',
                'db_connection_label' => 'Veritabanı Bsğlantısı',
                'db_connection_label_mysql' => 'mysql',
                'db_connection_label_sqlite' => 'sqlite',
                'db_connection_label_pgsql' => 'pgsql',
                'db_connection_label_sqlsrv' => 'sqlsrv',
                'db_host_label' => 'Veritabanı Host',
                'db_host_placeholder' => 'Veritabanı Host',
                'db_port_label' => 'Veritabanı Port',
                'db_port_placeholder' => 'Veritabanı Port',
                'db_name_label' => 'Veritabanı Adı',
                'db_name_placeholder' => 'Veritabanı Adı',
                'db_username_label' => 'Veritabanı Kullanıcı Adı',
                'db_username_placeholder' => 'Veritabanı Kullanıcı Adı',
                'db_password_label' => 'Veritabanı Parola',
                'db_password_placeholder' => 'Veritabanı Parola',

                'app_tabs' => [
                    'more_info' => 'Daha Fazla',
                    'broadcasting_title' => 'Yayınlama, Önbelleğe Alma, Oturum ve Sıra',
                    'broadcasting_label' => 'Yayın Sürücüsü',
                    'broadcasting_placeholder' => 'Yayın Sürücüsü',
                    'cache_label' => 'Cache Sürücüsü',
                    'cache_placeholder' => 'Cache Sürücüsü',
                    'session_label' => 'Session Sürücüsü',
                    'session_placeholder' => 'Session Sürücüsü',
                    'queue_label' => 'Queue Sürücüsü',
                    'queue_placeholder' => 'Queue Sürücüsü',
                    'redis_label' => 'Redis Sürücüsü',
                    'redis_host' => 'Redis Host',
                    'redis_password' => 'Redis Parola',
                    'redis_port' => 'Redis Port',

                    'mail_label' => 'Mail',
                    'mail_driver_label' => 'E-Posta Sürücüsü',
                    'mail_driver_placeholder' => 'E-Posta Sürücüsü',
                    'mail_host_label' => 'E-Posta Host',
                    'mail_host_placeholder' => 'E-Posta Host',
                    'mail_port_label' => 'E-Posta Port',
                    'mail_port_placeholder' => 'E-Posta Port',
                    'mail_username_label' => 'E-Posta Username',
                    'mail_username_placeholder' => 'E-Posta Kullanıcı Adı',
                    'mail_password_label' => 'E-Posta Parola',
                    'mail_password_placeholder' => 'E-Posta Parola',
                    'mail_encryption_label' => 'E-Posta Şifreleme',
                    'mail_encryption_placeholder' => 'E-Posta Şifreleme',

                    'pusher_label' => 'Yayıncı',
                    'pusher_app_id_label' => 'Yayıncı App Id',
                    'pusher_app_id_palceholder' => 'Yayıncı App Id',
                    'pusher_app_key_label' => 'Yayıncı App Key',
                    'pusher_app_key_palceholder' => 'Yayıncı App Key',
                    'pusher_app_secret_label' => 'Yayıncı App Secret',
                    'pusher_app_secret_palceholder' => 'Yayıncı App Secret',
                ],
                'buttons' => [
                    'setup_database' => 'Veritabanı Kurulumu',
                    'setup_application' => 'Kurulum Uygulaması',
                    'install' => 'Kurulum',
                ],
                'db_connection_failed' => 'Veritabanına bağlanılamadı, bağlantı ayrıntılarınızı kontrol edin',
            ],
        ],
        'classic' => [
            'templateTitle' => 'Adım 3 | Ortam Ayarları | Klasik Editör',
            'title' => 'Klasik Ortam Editörü',
            'save' => 'Kaydet .env',
            'back' => 'Form Sihirbazını Kullan',
            'install' => 'Kaydet ve Kurulum',
        ],
        'success' => '.env ayar dosyası kaydedildi.',
        'errors' => '.env ayar dosyası kaydedilemiyor, Lütfen elle oluşturunuz.',
    ],

    'install' => 'Kurulum',

    /**
     *
     * Installed Log translations.
     *
     */
    'installed' => [
        'success_log_message' => 'Laravel başarıyla kuruldu,  ',
    ],

    /**
     *
     * Final page translations.
     *
     */
    'final' => [
        'title' => 'Kurulum Tamamlandı',
        'templateTitle' => 'Kurulum Tamamlandı',
        'finished' => 'Webtorn Uygulaması başarıyla kuruldu.',
        'migration' => 'Migration &amp; Seed Konsol Çıktısı:',
        'console' => 'Uygulama Konsol Çıktısı:',
        'log' => 'Kurulum Log Griişi:',
        'env' => '.env Dosya Sonucu:',
        'exit' => 'Webtorn Kontrol Paneline Git',
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
        'title' => 'Laravel Güncelleyici',

        /**
         *
         * Welcome page translations for update feature.
         *
         */
        'welcome' => [
            'title'   => 'Güncelleyiciye Hoş Geldin',
            'message' => 'Güncelleme Sihirbazına Hoş Geldin.',
        ],

        /**
         *
         * Welcome page translations for update feature.
         *
         */
        'overview' => [
            'title'   => 'Genel Bakış',
            'message' => '1 güncelleme bulundu.|:number güncelleme bulundu.',
            'install_updates' => "Kurulum Güncellemeleri"
        ],

        /**
         *
         * Final page translations.
         *
         */
        'final' => [
            'title' => 'Bitti',
            'finished' => 'Uygulamanın veritabanı başarıyla güncellendi.',
            'exit' => 'Admin Kontrol Paneline Gitmek için tıkla',
        ],

        'log' => [
            'success_message' => 'Laravel başarıyla güncellendi, ',
        ],
    ],

];
