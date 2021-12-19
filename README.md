# grade_rabbit
simple rabbit example
Start: docker-compose up -d

usage:

  1:run first listener docker-compose exec php-first php listener.php

  2:run second listener docker-compose exec php-second php listener.php

  3(when 1 and 2 running):send message docker-compose exec php-main php sender.php
