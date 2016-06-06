# Norwalk-Aquarium-Email
Files for the email component of the Norwalk Aquarium project (RLMG).

Download these files by clicking the "Download ZIP" button, or by [using this link](https://github.com/davekobrenski/Norwalk-Aquarium-Email/archive/master.zip). (Or, even better, clone it)

###SMTP Connection Configuration
Edit the settings in the `smtp.json` file. This allows you to easily swap out the SMTP settings, if needed.

Your smtp.json file should look something like this:
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
http://yourserver.com/email/index.php
```

The IP address (or domain) component should match that of your webserver. A custom domain (if configured on your server) can also be used.

The output of that web page will look something like this screenshot:

![index.php screenshot](http://e.bbmdesigns.com/3G3W332k023M)
