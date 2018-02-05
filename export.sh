#!/bin/bash

cd /home/zirland/git/gtfs/

curl http://localhost/JDF/cisti.php
curl http://localhost/gtfs/cisti.php
curl http://localhost/gtfs/opravy.php

curl http://localhost/gtfs/feed_agency.php

curl http://localhost/gtfs/feed_jdf_route.php?oblast=515
curl http://localhost/gtfs/feed_jdf_route.php?oblast=516
curl http://localhost/gtfs/feed_jdf_route.php?oblast=556
curl http://localhost/gtfs/feed_jdf_route.php?oblast=557
curl http://localhost/gtfs/feed_jdf_route.php?oblast=558
curl http://localhost/gtfs/feed_jdf_route.php?oblast=595
curl http://localhost/gtfs/feed_jdf_route.php?oblast=91500
curl http://localhost/gtfs/feed_jdf_route.php?oblast=91501
curl http://localhost/gtfs/feed_jdf_route.php?oblast=91502
curl http://localhost/gtfs/feed_jdf_route.php?oblast=91503
curl http://localhost/gtfs/feed_jdf_route.php?oblast=91504
curl http://localhost/gtfs/feed_jdf_route.php?oblast=91505
curl http://localhost/gtfs/feed_jdf_route.php?oblast=91506
curl http://localhost/gtfs/feed_jdf_route.php?oblast=91507
curl http://localhost/gtfs/feed_jdf_route.php?oblast=91508
curl http://localhost/gtfs/feed_jdf_route.php?oblast=91509
curl http://localhost/gtfs/feed_jdf_route.php?oblast=91510
curl http://localhost/gtfs/feed_jdf_route.php?oblast=91511
curl http://localhost/gtfs/feed_jdf_route.php?oblast=910
curl http://localhost/gtfs/feed_jdf_route.php?oblast=505
curl http://localhost/gtfs/feed_jdf_route.php?oblast=103


curl http://localhost/gtfs/feed_vlak.php?color=008000
curl http://localhost/gtfs/feed_vlak.php?color=008983
curl http://localhost/gtfs/feed_vlak.php?color=000000
curl http://localhost/gtfs/feed_vlak.php?color=B51741
curl http://localhost/gtfs/feed_vlak.php?color=ECAE01
curl http://localhost/gtfs/feed_vlak.php?color=0094DE
curl http://localhost/gtfs/feed_vlak.php?vyhled=1

curl http://localhost/gtfs/feed_close.php

zip trains *.txt

exit;
