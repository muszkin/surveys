Surveys system
=

A survey system for dreamcommerce customer service.

Features:

**Surveys Yes/No**

**Surveys 1-10(NPS)**

**Surveys 1-10(NPS) for phone calls**

To reset cache:

1. log in to server surveys.dashboarddc.com

2. chroot --userspec=surveys /home/surveys /bin/sh

3. cd surveys.dashboarddc.com

4. php bin/console c:c && php bin/console c:c -e prod

if there is error from chmod use that outside of chroot

chown -R surveys:surveys /home/surveys/surveys.dashboarddc.com
