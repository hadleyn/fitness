#!/bin/bash

rsync -va ./ ubuntu@fitness.silentrunning.info:/var/www/html/fitness-dev/ --exclude '.git*' --exclude 'storage/*' --exclude 'vendor/*'
