Setup
To set up your new CI project, you will need to set some configurations. 

First open up /application/config/config.php and set the base_url to the url of the site you are developing. 

You may also want to change the cookie_prefix value to something else as it can cause conflicts with other sites running with that name.

Open up /application/config/database.php and set your database connection settings up in there.

If you wish to route requests to controllers based on subdomains (ex. api.mysite.com will route to /applications/controllers/api/) then leave the MY_Router.php file in the /application/core folder. If you wish to route the requests based on folders (ex. mysite.com/api/ routes to /applications/controllers/api/) then simply remove the file.

Included Libraries
This build of codeigniter comes pre packaged with a few useful libraries for development.

Curl
The curl library can be used to get files from remote sources. Here is a quick example:
$data = $this->curl->simple_get('http://www.google.com'); //data now = google.com's html

APNS, GCM and C2DM
The apn and c2dm libraries can be used to push notifications to iOS and Android devices. The apn library will need to be configured in /application/config/apn.php to point to the correct .pem certificate for sending push notifications.

For more info on GCM read here: http://developer.android.com/guide/google/gcm/index.html

Here is an example of it in use:
gcm:
$this->load->library('gcm');
$this->gcm->send($token,$servertoken,$message);


c2dm:
$this->load->library('c2dm');
if ($this->c2dm->send($push_token, $server_token, $message) == 'error') {
	return false;
}

apn:
$this->load->library('apn');
$this->apn->connectToPush();
$r = $this->apn->sendMessage($push_token, $message);
$this->apn->disconnectPush();



Get
This library is autoloaded, using $this->load is not necessary. This library can be used to manage variables POSTed or GETed to the server. It is similar to the CI input class, but gives the developer a place to easily manage variable sanitization and management without extending the core. Here is a rundown of all the methods that get has to offer:

The get class is automatically assigned properties based on the parameters sent to the server. So, if you post or send a get variable of 'somevar' you can access it by using $this->get->somevar; If there is a conflict of names the GET variable will take precidence.

$this->get->assoc(array $ignore)
This method returns an associative array of all get/post variables, but will ignore keys specified in the $ignore array.

$this->get->hasKeys('key1','key2')
This method checks whether the keys supplied as arguments were sent to the server. If you are trying to check whether the variables have been set via a HTML form, run $this->get->validateKeys(); first.

$this->get->sent()
Returns true if get/post variables are present, or false if they are not.

Datamapper - version 1.8.2

Ion Auth - installed

Running from CLI
To run any of your scripts from the CLI, follow the instructions here:
http://codeigniter.com/user_guide/general/cli.html