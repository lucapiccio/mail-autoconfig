# mail-autoconfig
PHP Script to generate the autoconfig for mailserver (autodiscover)

This simple php site is useful to create a vhost autoconfig.example.com

It will generate the correct config for Outlook or Thunderbird for SSL mail.example.com where the domain is extract from header connection.

You need to configure correctly the rewrite/redirect on the server to match the Mail's Client autoconfig urls to the index.php

