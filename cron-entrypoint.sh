#!/bin/bash
eval $(printenv | awk -F= '{print "export " "\""$1"\"""=""\""$2"\"" }' >> /etc/profile.d/environmnets.sh)
cron -f -l 