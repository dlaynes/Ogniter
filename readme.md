The site worked well enough, but nowadays I'd use TimescaleDB for the database ranking records (because having 500+ tables is terrible), and a light modern MVC Framework based on PHP or any other language.

You can install the default countries with the following command:

`php artisan ogame:update-communities`

https://github.com/dlaynes/Ogniter/blob/master/app/Ogniter/Api/Remote/Ogame/Command/UpdateCommunities.php

Please take a look at the other registered commands of the project
