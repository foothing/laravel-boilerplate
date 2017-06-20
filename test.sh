#!/bin/bash
revisioncount=`git log --oneline -n 1 | cut -d ' ' -f 1`
echo "<?php \$_v = md5('$revisioncount');" > "../../revision"
