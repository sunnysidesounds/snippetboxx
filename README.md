snippetboxx
===========


Coding and sniplet tagging application.


CodeIgniter Updates Note:
----------------------------------
When you upgrade Codeigniter core you need to replace all files and directories in your "system" folder and replace your index.php file per their CI instructions. 

But since Snippetboxx uses custom Session not part of the CI core. You need to either keep the existing system/libraries/Session.php or replace it with the backup in dev_assets/Session.php

The basic steps:

1. Replace index.php

2. Replace diriectory "system" (keeping the exisiting system/libraries/Session.php file)

3. f you replace system/libraries/Session.php. You can obtain a backup located in the dev_assets directory.




