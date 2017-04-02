#!/bin/bash

cd /home/zirland/git/gtfs/

curl http://localhost/gtfs/feed.php

zip trains *.txt

feedvalidator.py trains.zip 

exit;
