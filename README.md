# CORD-19 Search Engine

## Set-Up

bin/solr start -c -p 8983 -s example/cloud/node1/solr

bin/solr start -c -p 7574 -s example/cloud/node2/solr -z localhost:9983

bin/solr create -c cord19 -s 2 -rf 2 -n cord19_configs

bin/post -c cord19 cord19/*.json

bin/solr delete -c cord19

bin/solr stop -all





bin/solr start

bin/solr create -c cord19 -d cord19_configs

