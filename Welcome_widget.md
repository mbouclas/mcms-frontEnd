# Welcome widget
It is used to provide a unique experience on our customer's dashboard.
We can setup both links and components on it.

## Setup
First thing to do is setup our config which is the frontEnd. We need a
new key called `welcomeWidget`. Under that we need the links array,
so we now have :
```
'welcomeWidget' =>
    [
        'links' => []
    ]
```

### Link type
```
    'title' => 'Manage your menus',
    'link' => ['type' => 'href', 'link' => 'menu-manager'],
    'description' => 'Add/remove/edit website menus',
    'settings' => []
```

The above example will link somewhere in our system where the 
route name is `menu-manager`.

### Component type
Components are a bit more complex. All components open in a dialog,
so mind that. Some components need configuration values which we can
easily pass via the `settings.local` array. An example of a component
that will prompt the admin to create a new user but with some roles and 
permissions prefiled.
 
```
[
    'title' => 'Add an author',
    'link' => ['type' => 'component', 'link' => '<edit-user user="VM.User" options="VM.options"></edit-user>'],
    'description' => 'Add a new author to the system',
    'acl' => ['type' => 'level', 'permission' => 98],
    'settings' => [
        'locals' => [
            'options' => [
                'preset' => [
                    'roles' => ['author', 'admin'],
                    'user_permissions' => ['create-post', 'edit-user']
                ]
            ]
        ]
    ]
],
```

Note that we need to pass an options object and this is done via
the locals array. Everything under it will be translated into objects
and passed into the VM as such. Note that this particular component
expects the user to be undefined, so we omitted the `User` property
from the locals. Notice that we have an ACL property set to check
if the user is of level 98 and above