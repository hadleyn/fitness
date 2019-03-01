#!/bin/bash

rsync -va ./ ubuntu@fitness.silentrunning.info:/var/www/html/fitness-dev/ --exclude '.git*' --exclude 'bootstrap*' --exclude 'storage*'
