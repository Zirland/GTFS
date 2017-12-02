#!/bin/bash

cd /home/zirland/git/gtfs/

curl http://localhost/gtfs/cisti.php
curl http://localhost/gtfs/opravy.php

curl http://localhost/gtfs/feed_agency.php

curl http://localhost/gtfs/feed_vlak.php?color=008983
curl http://localhost/gtfs/feed_vlak.php?color=000000
curl http://localhost/gtfs/feed_vlak.php?color=B51741
curl http://localhost/gtfs/feed_vlak.php?color=ECAE01
curl http://localhost/gtfs/feed_vlak.php?color=008000
curl http://localhost/gtfs/feed_vlak.php?color=0094DE

curl http://localhost/gtfs/feed_vlak_new.php?color=008983
curl http://localhost/gtfs/feed_vlak_new.php?color=000000
curl http://localhost/gtfs/feed_vlak_new.php?color=B51741
curl http://localhost/gtfs/feed_vlak_new.php?color=ECAE01
curl http://localhost/gtfs/feed_vlak_new.php?color=008000
curl http://localhost/gtfs/feed_vlak_new.php?color=0094DE

curl http://localhost/gtfs/feed_jdf_route.php

curl http://localhost/gtfs/feed_close.php	

zip trains *.txt

feedvalidator.py trains.zip

exit;
