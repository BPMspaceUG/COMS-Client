1. Prerequisite
   1. Installed LIAM2 (normally on an other server) - https://github.com/BPMspaceUG/LIAM2
   2. URL and Port of the LIAM2 installation
   3. Generated machine token to access
   4. Git client installed on machine
2. TODO
   1. "git clone https://github.com/BPMspaceUG/LIAM2-Client.git"
   2. copy /inc/api.EXAMPLE_secret.inc.php to /inc/api.secret.inc.php and set: 
        1. $headers[] = 'Cookie: token=[ENTER TOKEN HERE]';
        2. $url = "[ENTER URL HERE]";
