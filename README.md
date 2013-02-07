snippetboxx
===========


Coding and sniplet tagging application.


CodeIgniter Update Note on Customization:
----------------------------------
When you upgrade Codeigniter core you need to replace all files and directories in your "system" folder and replace your index.php file per their CI instructions. 

But since Snippetboxx uses custom Session not part of the CI core. You need to either keep the existing system/libraries/Session.php or replace it with the backup in dev_assets/Session.php

The basic steps to custom upgrading:

1. Replace index.php

2. Since custom environment variables are setup in the index.php we will need to replace line 20-24 that is just this:
	
	<code>define('ENVIRONMENT', 'production'); </code>

    With our custom environment variable logic:

    	<code>
	if ($_SERVER['SERVER_NAME'] == 'www.snippetboxx.com'){define('ENVIRONMENT', 'production');}
	else { define('ENVIRONMENT', 'development'); }
	</code>

3. Replace diriectory "system" (keeping the exisiting system/libraries/Session.php file)

4. if you replace system/libraries/Session.php. You can obtain a backup located in the dev_assets directory.

	







