##Setup
To set up your new CI project, you will need to set some configurations. 

First open up /application/config/config.php and set the base_url to the url of the site you are developing. 

You may also want to change the cookie_prefix value to something else as it can cause conflicts with other sites running with that name.

Open up /application/config/database.php and set your database connection settings up in there.

If you wish to route requests to controllers based on subdomains (ex. api.mysite.com will route to /applications/controllers/api/) then leave the MY_Router.php file in the /application/core folder. If you wish to route the requests based on folders (ex. mysite.com/api/ routes to /applications/controllers/api/) then simply remove the file.

##What's Different

There are some distinct differences between this version of Codeigniter and the stock version.

###Templating

I've built in a native templating system that treats views as partial templates that get loaded into a master template.

In the views/templates folder you will see a file named default.php. This is the default template that will load if no template is specified. The view will load in the main template wherever the $content variable is.

You can create your own templates and tell the framework to load it by using the following code:

    $this->template = 'templates/your_template.php';
    
###View Loading Handled Automagically

I thought it was a bit cumbersome to have to include the whole $this->load->view(); code in every single controller method.

The Codeignited framework automatically loads views based on the name of the controller/method. For example Blog::page() would automatically attempt to load 'views/blog/page.php' as it's view. You can override this and specify your own views by using:

    $this->view = 'folder/your_view.php';
    
##Data Output

Data output is handled in non-api controllers by using the $this->data[] array. For example in my controller if I had the following:

    class Books extends MY_Controller {
    	public function __construct() {
        	parent::__construct();
        }
        public function index() {
        	$this->data['hello'] = 'Hello World';
        }
    }
    
I could access the variable '$hello' from my view like so:

	<?= $hello; //echoes 'Hello World' ?>
    
##Built-in API support:

The Codeignited framework has built-in API support and will treat any folder in the controllers folder with the string 'api' in it as an API. For instance if my folder structure was like so:

/application
	/controllers
    	/my_api
        	api_call.php
            
The 'api_call.php' file would be treated as an API call with no further work needed by the developer.

The api has a default response of

    { "success": true }
    
To add items to the JSON/XML response you only need to populate the $this->out[] array. For instance if my controller was like so:

    class Books extends MY_Controller {
    	public function __construct() {
        	parent::__construct();
        }
        public function index() {
        	$this->out['hello'] = 'Hello World';
        }
    }

The response would look like:

    {
        "success":true,
        "hello": "Hello World"
    }

To get an XML response you only need to provide a GET parameter to the HTTP request of 'format=xml'.

Additionally, you may default any controller or method to output JSON/XML data by declaring the following:

    $this->isApi = true;

##Included Libraries
This build of codeigniter comes pre packaged with a few useful libraries for development.

###Curl
The curl library can be used to get files from remote sources. Here is a quick example:

    $data = $this->curl->simple_get('http://www.google.com'); //data now = google.com's html

###APNS, GCM and C2DM
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



###Get

I am currently working on making this library better.

This library is autoloaded, using $this->load is not necessary. This library can be used to manage variables POSTed or GETed to the server. It is similar to the CI input class, but gives the developer a place to easily manage variable sanitization and management without extending the core. Here is a rundown of all the methods that get has to offer:

The get class is automatically assigned properties based on the parameters sent to the server. So, if you post or send a get variable of 'somevar' you can access it by using $this->get->somevar; If there is a conflict of names the GET variable will take precidence.

$this->get->assoc(array $ignore)
This method returns an associative array of all get/post variables, but will ignore keys specified in the $ignore array.

$this->get->hasKeys('key1','key2')
This method checks whether the keys supplied as arguments were sent to the server. If you are trying to check whether the variables have been set via a HTML form, run $this->get->validateKeys(); first.

$this->get->sent()
Returns true if get/post variables are present, or false if they are not.

###Datamapper

version 1.8.2

###Ion Auth

installed

###Running from CLI

To run any of your scripts from the CLI, follow the instructions here:
http://codeigniter.com/user_guide/general/cli.html