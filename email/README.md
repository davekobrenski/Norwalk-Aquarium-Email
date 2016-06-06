# JFK-Email
Files for the email component of the JFK project (RLMG).

Download these files by clicking the "Download ZIP" button, or by [using this link](https://github.com/davekobrenski/JFK-Email/archive/master.zip). (Or, even better, clone it)

###Email Configuration
Open the `send.php` file in a text editor and, if needed, edit the variables at the top of the script (`$from_email`, `$from_name`, etc). It looks like this:

```php
/** CONFIGURATION */

//basic email configuration:
$from_email = "JFK_Interactive@jfklfoundation.org"; //email address of sender here
$from_name = "JFK Presidential Library"; //name of sender here
$subject = "Your Collection of Jackie's Dresses";

//administrator confirmation emails
//these settings are for the email that gets sent for the purpose of collecting visitor email addresses:
$adminEmail = "dave@rlmg.com"; 
$adminName = "JFK Library";
$adminSubject = "JFK Museum: Visitor email";

//specify the filename of the json file that holds the mail smtp settings
//this file should exist in the same directory as the send.php file.
//(the json file contains the settings needed to connect to the mail server)
$mailSettingsFile = "smtp.json"; //i.e., "smtp.json"
```

###SMTP Connection Configuration
Edit the settings in the `smtp.json` file — or, optionally, create your own `your-name.json` file and specify this file name in the `$mailSettingsFile` variable. This allows you to easily swap out the SMTP settings, if needed.

Your smtp.json file should look like this:
```javascript
{
	"mailHost": "mail.server.com",
	"mailUser": "you@company.com",
	"mailPass": "password",
	"mailEncrypt": "tls",
	"mailPort": 587
}
```

###Installation
Install all files into a public directory on the web server. Navigate to the `index.php` file in a web browser to test the email functionality.

The page will output the URL of the `send.php` script that will be posted to from the exhibit apps (our developers will need that URL to add to their code). 

For example, if you install the files in a directory called `email` in the root directory of your webserver, you would navigate to it in your web browser:

```
http://184.106.183.75/email/index.php
```

The IP address component should match the IP address of your webserver. A custom domain (if configured on your server) can also be used.

The output of that web page will look something like this screenshot:

![index.php screenshot](http://jfk.rlmg2.com/email/images/jfk-test.jpg)
