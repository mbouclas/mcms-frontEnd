# User configuration on a new installation
- Before you even begin make sure you have read very well on how Laravel uses
Notifications and Mailables, otherwise you will not get it to work properly.

## Setting up
The installer should have taken care of everything, but in case you're upgrading make sure the 
following are true
* The `Illuminate\Notifications\NotificationServiceProvider` is setup
* You have published the notifications and auth assets
* The frontEnd config contains the user array
* In mail config you have setup the default mail address/name 

If all of the above is true, then you're good to go.

## Concepts
* There are 2 hooks executed during the registration process. The before, which can be used to 
alter your user array and the after which is fired once the user is created. The after hook is
perfect for creating registration flows.
* There are 3 default types for user registration, open, verified, moderated. 
They will be explained bellow.
* You are free to create a custom 4th registration type, so long as you know what you're doing.
* We are using mailables for email sending apart from the user reset password cause this is a
Laravel thing so we need a notification class for that.
* To override default behaviour we need to edit the frontEnd config file

#### Registration types
##### - Open
This is pretty straight forward, the user registers and is immediately activated. If there is a 
welcome email class set, then one is sent. 
##### - Verified
This is the typical user registration flow. User registers, email is sent containing a confirmation
link. Once clicked, the user is verified and then activated in the system
##### - Moderated
This looks like the verified type, but after the user has confirmed the email by clicking
the confirmation link, tha user is not activated. He is flagged for moderation instead and 
an email is sent to the admin who will activate him manually.

#### Emails
For every action we have a mailables array which need to contain the handler class, the view 
to be used in the email and the mail subject. You can then use these in your custom flow 
instead of hard-coding them. There is a `SendMailViaConfig` class which allows you to easily
send an email to the user by using these configs like so :
```
$this->mailer = new SendMailViaConfig();
//user must be an object and the config must be valid
$this->mailer->send('frontEnd.user.mailables.activation', $user); 
```
#### The user reset password
In Laravel resetting passwords comes out of the box, sort of. You will need to modify the 
email sent to the user containing the reset link. This is a bit more complex than it should
but here is the flow.

There are 2 ways to override it:
* By setting the `frontEnd.user.mailables.lostPassword.handle` to a class of your own which must
  be in the form of `Mcms\Core\Mailables\ResetPasswordNotification`
* By extending the user model and overriding the `sendPasswordResetNotification` with one of 
 yours. It must return a notification like `Mcms\Core\Mailables\ResetPasswordNotification`
 
You will then need to go to `views/vendor/notifications` and change the templates to something
you like

## Logging in users
The default condition is that we login users that are active. To change this, extend the
`Mcms\FrontEnd\Http\Controllers\Auth\AuthController` and override the `credentials` method
and add anything you need to be checked in the DB.
Don't forget to override the `sendFailedLoginResponse` to send the error messages back to the
view.