This application allows users to subscribe to receive new articles from sites
Articles are sent once via the provided email

### Set up
- Create a copy of `.env.example` and save it as `.env`
- Create an application key by running the command `php artisan key:generate`

#### Database setup
Create a database to use in storing the application data
Open the `.env` file and update the following lines in the `DB` section with your environment specific settings
```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=<NAME OF THE DATABASE YOU CREATED>
DB_USERNAME=root
DB_PASSWORD=
```
Create the database tables by running the command:<br>**`php artisan migrate --seed`**
  The command will also seed some 3 example sites in the database

#### Email setup
To ensure emails will be sent without any problem, update the `MAIL` section of the `.env` with specific settings of your preferred mail sender. I used mailtrap for instance:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=<MAILTRAP USERNAME>
MAIL_PASSWORD=<MAILTRAP PASSWORD>
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=<MAILTRAP EMAIL>
```

## Running the application
After you have set up everything you can now run the application.

First, start the queue by running the command:<br>**`php artisan queue:work`**

Open a new terminal window or tab and start the server as well using the command:<br>**`php artisan serve`**

## API
Each api request has a common response with the following pieces of data:
- **`success`** - A true/false value that indicates whether the request being attempted succeeded 100%.
- **`message`** - A status message corresponding to the result of the request
- **`data`** - Mixed data, if any, sent by the API back to the client
- **`errors`** - An array of validation errors detected in the data that was sent
  
You can send requests from your frontend or test via postman

### Creating an article
To create a new article for a particular site, the following is the request information

**Endpoint:** /api/posts/create<br> **Method:** POST<br> **Body:**
```
{
    site_id: 1,
    title: "Meta Platform's stock takes a nose dive",
    description: "Investors are worried about Meta's future......"
}
```

**Sample Responses**
<br>Successful post creation
```
{
    success: true,
    message: "The post has been created successfully",
    data: {
        // Post data
    }
}
```

Validation errors present
```
{
    success: false,
    message: "Data validation errors detected. Fix the errors and try again",
    errors: [
        "The selected site id is invalid.",
        "The title field is required."
    ]
}
```

### Subscribing to a site
To subscribe a user to get alerts from a site once an article is published, send the site_id and subscriber's email as shown in the example:

**Endpoint:** /api/subscriptions/new <br>**Method:** POST <br> **Body:**
```
{
    site_id: 1,
    email: "john@example.com"
}
```

**Sample Responses**
<br>Successful subscription
```
{
    success: true,
    message: "User has been subscribed to the website successfully"
}
```

User was already subscribed to the site
```
{
    success: false,
    message: "User is already subscribed to the website"
}
```

## Sending Emails to Subscribers
To send emails to subscribers,
- Ensure the queue is running. If you did not start it, do so by running the command:<br>**`php artisan queue:work`**

Open a separate tab and run the command:<br>**`php artisan posts:send_alerts`**

If there are new posts for any site in the database, they will be sent to every subscriber via the running queue

**PS:** You can also schedule the command to run periodically in your `App\Console\Kernel` e.g every half an hour if you are using a scheduler

