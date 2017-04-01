#!/bin/bash

curl http://localhost/gtfs/feed.php

zip trains *.txt

feedvalidator.py trains.zip 

exit;
