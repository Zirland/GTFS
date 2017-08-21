#!/bin/bash

cd /home/zirland/git/gtfs/v2/

curl http://localhost/gtfs/opravy.php
curl http://localhost/gtfs/migracestop.php

curl http://localhost/gtfs/feed_agency.php

curl http://localhost/gtfs/feed_vlak.php?color=008983
curl http://localhost/gtfs/feed_vlak.php?color=000000
curl http://localhost/gtfs/feed_vlak.php?color=B51741
curl http://localhost/gtfs/feed_vlak.php?color=ECAE01
curl http://localhost/gtfs/feed_vlak.php?color=008000
curl http://localhost/gtfs/feed_vlak.php?color=0094DE

curl http://localhost/gtfs/feed_close.php

# curl http://localhost/gtfs/feed_transfer.php

zip trains *.txt

feedvalidator.py trains.zip 

exit;
