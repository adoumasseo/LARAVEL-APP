# MAIL-APP
It's a simple mail system based on laravel and mailgun free plan

## Installation

First you need to clone this repository
```sh
git clone https://github.com/adoumasseo/LARAVEL-APP.git
```
Then go into the folder ```mail_app_2``` and from there run : 
```composer install```

<br>
After that you have to set up the Environment file and generate the application key using 
```php artisan key:generate```

<br>

Ok i think you can continue from there.
If you have a problem setting up everything just email me <adoumasseo@gmail.com>


## Usage

There is just one endpoint ```localhost/api/send-mail```
```POST``` for the verb and that it <br>
For the body of the request here is en example
```json
{
  "recipients": ["adoumasseortniel@gmail.com"],
  "subject": "Test Email 16:02",
  "content": "This is the content of the email."
}
```

```recipients``` have to be an array of valid email <br>
```subject``` and ```content``` can't be empty <br>

That's all and as i'm tired gonna live it like this and come later to make it look more professional 