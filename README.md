bitcoin_gateway
===============

A PHP/Mysql Bitcoin Payment Gateway installed and operated on the Bitcoin server that can be initiated and report to a remote server in, for example, a shared hosting account.

This script assumes you already have a working installation of the bitcoind server installed and running on the server. This has only been tested on a server running Ubuntu 12.04.

You will also need to install Apache, PHP and MySql on the server. You will also need to install php-curl. There is one database and three tables that will need to be installed and the schema for them is included. One table (named watching) is a temp table used while parsing a transaction. Once a transaction has completed being processed it will move the results from that temp table into the others for storage and as a permanent record. 

It will also connect to a remote server on, let's say, a shared hosting server and will send the results there via curl.

The actions of the script can be adjusted to react to any number of confirmations you configure. They are configurable for both when the script sends a notcie of payment to the shared server (the default is one confirmation) and the second setting is for when the script moves the deposited Bitcoin to a different wallet/address (aka "cold storage") and the default for this second setting is set at 3 confirmations.
