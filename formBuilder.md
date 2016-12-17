# Form builder

## Architecture

### Schemas
A schema should adhere to the same principals that the default admin one does. To create a new schema
we need to use the API to either setup one from scratch or just extend/modify the admin one (preferred method).

When extending a schema, we usually want to add extra configuration or settings to an existing one. It will be a
rare case if we need to add a new field type, but we can easily do so. 

### Service (API)

#### __construct($name)
```
$builder = new FormBuilderService($name);
```

#### create(array $options = [])
```
$builder->create();
```

#### addField(array $field)
```
$builder->addField($field);
```

#### removeField(array $field)
```
$builder->remove($field);
```

#### addToField(array $param, $key = 'params')
```
$builder->addToField($param);
$builder->addToField($config, 'config');
$builder->addToField($settings, 'settings');
```

#### removeFromField($key = null, $location  = 'params')
remove something from the schema
```
$builder->removeFromField($type, $key); //removes from params
$builder->removeFromField($type, $config, 'config');
$builder->removeFromField($type, $settings, 'settings');
```

#### find($type, $key, $location = 'params')
Find a node in the schema
```
$builder->find($type, $key); //look in params
$builder->find($type, $key, 'config');
$builder->find($type, $key, 'settings');
```

#### get()
Return the schema
```
$builder->get(); //returns collection
```

#### cloneSchema($filename)
Clones an existing schema loaded from disk. Can be chained with other methods
```
$builder->cloneSchema($filename); //returns collection
$builder->cloneSchema($filename)->addToField($param); 
$builder->cloneSchema($filename)->addToField($param)->save($filename); 
```

#### save($filename = null)
if $filename is empty, we will save it using the schema name
```
$builder->save($filename);
```

#### delete($filename = null)
if $filename is empty, we will try to delete it using the schema name
```
$builder->delete($filename);
```

#### load()
This will load a schema from file
```
$builder = new FormBuilderService($filename);//notice how the schema name is now the filename
$builder->load();
```


### Static interface (FormBuilder)
* creates blank schema
```
FormBuilder::create($name, $options);
```
* extends the default schema 
```
FormBuilder::schema($name)->addParam(array $param)->addConfig(array $config)->addSetting(array $setting);
```
* delete from the schema
```
FormBuilder::schema($name)->removeParam($key)->removeConfig($key)->removeSetting($key);
```
* returns in the current schema
```
FormBuilder::find($key, $location = 'params');
```
* clones the schema
```
FormBuilder::clone($name);
FormBuilder::clone($name)->save($filename);
FormBuilder::clone($name)->param(array $param)->config(array $config)->setting(array $setting)->save($filename);
```
* save to file
```

FormBuilder::save($filename);
```
* load from file
```
FormBuilder::load($filename);
```
* delete file
```
FormBuilder::delete($filename);
```

### Providers
A provider defines the flow that the whole process will take, from form submission and on. Each provider needs to
implement the `FormBuilderProvider` interface in order to be used. 

Providers are responsible for receiving the form data, doing any sort of processing needed, and then doing something
with the form data (send email, save to db, send notification)

Note : To get the `Mailchimp` provider to work you need to install
and configure the `mbouclas/mcms-mailchimp` package

#### API
##### __construct(Request $request)
```
use Slugify
* public $request// The initial request object
* public $validator = 'SomeValidatorClass' 
* public $config = 'OurConfigString' 
* public $route = 'OurRouteString' //we get url and stuff from it
* protected $event // an instance of the Event

$provider = new FormBuilderProvider($request);
```


##### controller()
Return the class name of the controller registered in the provider
```
$provider->controller();
```

##### url()
Return the class name of the controller registered in the provider
```
$provider->url();
```

##### isValid()
Return if the data submitted are valid or not
```
$provider->isValid();
```

##### validate()
Validate our data
```
$provider->validate();
```

##### process()
Process our data
```
$provider->process();
```

##### result()
Return the result of the processing
```
$provider->result();
```


The flow
* we get the form data from `Request`
* validate the data against our validator (if any is set)
* process the data. This could be anything from sending a simple email to saving to the DB
* return something. Depending on 

The following events are fired during the flow
* `formProvider.data.received` When we get the data from the form
* `formProvider.data.validated` When we have validated the form data
* `formProvider.data.processed` When we have processed the form data


### Notifications
We are using the `mcms-notifications` package to send notifications. During the installation there should be 
a default contact form notification waiting for us, if not, create it as a simple mail. 
You can modify the default notification in `FrontEnd\Notifications\ContactFormEmail`

So, if you want to send a notification in the `process` section of your provider then you should use that API for 
ease of use

### Configurator
The configurator is used to configure the provider. So it loads all the necessary config values from a published config
or from some other source. The configurator is declared as a public in the provider but can be changed in the config. 
When the provider asks for the config it looks for the `get` method like so. Example of the provider constructor

```
class EmailProvider {
    use Sluggable;
    
    protect $configurator = Configurator::class;
    public $config;
    public $route;
    public $model;
    protected $defaultRoute;
    
    __construct(Request $request){
        
        $this->config = (Config::has('configurator')) ? 
        (new Config::get('configurator))->get() : 
        (new $this->configurator)->get();
        
        $this->defaultRoute = $this->config->route();
        $this->model = $this->config->model();//get a model if we have a DB or a collection otherwise
        
    }
}
```

### Item selector interface
This will connect the form builder to our item selector. It will return, as expected, the form ID, the title
of the form and the form builder class as a string.

## Widget
Invoke your forms in blade by doing something like `@Form('contact-form')` where `contact-form` is the form slug.
The form is build right in your views. 

You can also use this widget in your components stored in the DB, like pages in description or settings, or wherever
really, so long as you don't forget to compile first. So, you would have the same syntax in your field, but then 
in your view, you need to compile it like so :
```
@Compile($page->description)
```
which will get the widget out of the value and compile it. 

Word of caution, the `@compile` directive is REALLY slow, so use it wisely (low traffic sites mostly or sites with dedicated
server). The `@Form` directive, just like any compiled directive is also slow, but not as slow as the `@Compile`.
The `@Compile` suffers from the extra disadvantage that it is not cached, so each time you call it, there is nothing
cached in the filesystem, which makes it even slower.

The default widget view is under `forms.widget` but you can change that by passing a config option to the directive like 
so : 
```
@Form('contact-form', ['view' => 'forms.subsciption-form'])
```

Note that any variables you pass from the view as the second parameter, are passed down to your view. So,
```
@Form('contact-form', ['key' => 'value'])
```
This means that in your widget view you now have a `$key` variable available to you as a blade variable `{{ $key }}`

# Custom settings template
You can customize your forms by creating a settings template in the `config/formBuilder.php`
just add the following in the array and it  will show up in the settings field of your model.
```
'settings' => [
    [
        "varName" => 'labelSuccess',
        "default" => 'forms.onSuccess',
        "type" => "text",
        "label" => 'Label on success',
    ],
    [
        "varName" => 'template',
        "type" => "select",
        "label" => 'Form template',
        "options" => [
            [
                "default" => TRUE,
                "label" => "Default form template",
                "value" => "widget"
            ],
        ],
    ],
],
```
