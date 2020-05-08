Skip to content
Search or jump to…

Pull requests
Issues
Marketplace
Explore

@FKMLJF
caouecs
/
Laravel-lang
8.5k
158
4.4k2k
Code Issues 0 Pull requests 2 Actions Projects 0 Wiki Security Insights
Laravel-lang/src/hu/passwords.php
@caouecs caouecs feature: password.throttled
3f7a82f on Nov 1 2019
@caouecs@andrey-helldar
Executable File  21 lines (19 sloc)  911 Bytes

<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Password Reminder Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match reasons
    | that are given by the password broker for a password update attempt
    | has failed, such as for an invalid token or invalid new password.
    |
    */

    'password'  => 'A jelszónak legalább hat karakterből kell állnia és egyeznie kell a jelszó megerősítéssel.',
    'reset'     => 'Az új jelszó beállítva!',
    'sent'      => 'Jelszó-emlékeztető elküldve!',
    'throttled' => 'Please wait before retrying.',
    'token'     => 'Ez az új jelszó generálásához tartozó token érvénytelen.',
    'user'      => 'Nem található felhasználó a megadott email címmel.',
];

