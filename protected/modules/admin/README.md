Yii - Admin Installation
=====================

Configure
---------

Ubah config main anda :

    return array(
        #...
        // autoloading model and component classes
        'import'=>array(
            'application.models.*',
            'application.components.*',
            'application.modules.user.models.*',
            'application.modules.user.components.*',
        ),

        #...
        'modules'=>array(
            #...
            'admin'=>array(
                # method enkripsi (php hash function)
                'hash' => 'md5',

                # Kirim aktivasi email
                'sendActivationMail' => true,

                # Ijinkan akses untuk non aktif user
                'loginNotActiv' => false,

                # Aktifkan pengguna saat registrasi (hanya sendActivationMail = false)
                'activeAfterRegister' => false,

                # Otomatis login setelah registrasi
                'autoLogin' => true,

                # registrasi path
                'registrationUrl' => array('/user/registration'),

                # reset password path
                'recoveryUrl' => array('/user/recovery'),

                # Form Login path
                'loginUrl' => array('/user/login'),

                # halaman setelah login
                'returnUrl' => array('/user/profile'),

                # halaman setelah logout
                'returnLogoutUrl' => array('/user/login'),
            ),
            #...
        ),

        #...
        // application components
        'components'=>array(
        #...
            'db'=>array(
            #...
                'tablePrefix' => 'tbl_',
            #...
            ),
            #...
            'user'=>array(
                // enable cookie-based authentication
                'class' => 'WebUser',
                'allowAutoLogin'=>true,
                'loginUrl' => array('/user/login'),
            ),
        #...
        ),
        #...
    );

Ubah pengaturan console:

    return array(
        #...
        'modules'=>array(
            #...
            'user'=>array(
                # encrypting method (php hash function)
                'hash' => 'md5',

                # send activation email
                'sendActivationMail' => true,

                # allow access for non-activated users
                'loginNotActiv' => false,

                # activate user on registration (only sendActivationMail = false)
                'activeAfterRegister' => false,

                # automatically login from registration
                'autoLogin' => true,

                # registration path
                'registrationUrl' => array('/user/registration'),

                # recovery password path
                'recoveryUrl' => array('/user/recovery'),

                # login form path
                'loginUrl' => array('/user/login'),

                # page after login
                'returnUrl' => array('/user/profile'),

                # page after logout
                'returnLogoutUrl' => array('/user/login'),
            ),
            #...
        ),
        #...
    );

Install
-------

Run command:
    yiic migrate --migrationPath=admin.migrations

Input admin login, email and password
