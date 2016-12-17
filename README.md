# FrontEnd

## View composers
Upon installation a new service provider is installed "ViewComposerServiceProvider".
This provider is responsible for containing all of your view composers, for example menus, sidebars etc.
It comes with a menu composer which looks for header-menu and footer-menu slugs to pass them on to the
partials.header and partials.footer views as $HeaderMenu and $FooterMenu respectively.
Any new composers should be registered in that service provider

## Editable regions
An editable region is a part in the layout that you can either allow the user to add types of content or you 
can add stuff by using a class. It needs to be configured in the "editableRegions.php" file in the config folder.
Each region can be either of type generic or class. Each item must have a type and an item property to be valid
 ```
 [
    'type' => 'html',
    'item' => 'dasfasdf'
 ]
 ```
 
 OR
 
 ```
  [
     'type' => 'image',
      "item" => [
             "src" => "http://img-9gag-fun.9cache.com/photo/aWMxG3A_460s.jpg",
             "href" => "http://9gag.com/gag/aWMxG3A",
             "title" => "A post",
             "target" => "_blank",
             "description" => "A cool post bro"
         ]
     ]
  ]
 ```

If it is of type class you need to provide the class name

```
[
 "featured" => [
            "label" => "Featured Items",
            "slug" => "featuredItems",
            "type" => "class",
            "class" => "FrontEnd\\EditableRegions\\LatestPages",
            ]
]
```

This allows for creating sort of widgets that the user cannot edit (e.g. latest articles) but you can allow the user
to change custom settings of yours, like results per page.

```
"options" => [
                [
                    "varName" => "limit",
                    "label" => "Number of items",
                    "type" => "text",
                    "options" => NULL
                ],
                [
                    "varName" => "orderBy",
                    "label" => "Order by",
                    "type" => "select",
                    "options" => [
                        [
                            "default" => TRUE,
                            "label" => "Title",
                            "value" => "title"
                        ],
                        [
                            "default" => FALSE,
                            "label" => "Creation date",
                            "value" => "created_at"
                        ],
                        [
                            "default" => FALSE,
                            "label" => "Custom",
                            "value" => "custom"
                        ]
                    ]
                ]
            ]
```

This can be very powerful when you need to add custom logic to your area or need to be sure that the user will not
"break" something.

The generic type is versatile and extremely powerful. It allows any type of content to be presented in that area.
The default types are :
* HTML : plain html, can be anything, so be careful in your templates
* image : An image accompanied by meta data like link and alt-title
* item : Using the ItemSelector class, you get items from any module. Don't forget to "process()"
* structured-data : you decide what the user should input. Allows for default data types

### Using the API
Start using the API by filtering the region, for example the front page regions is what you want
```
$regions = $regions->filter('frontPage');
```

The $regions object now holds all the editable regions for the frontPage layout. The quickest way to proceed 
is to do a simple ``` $regions->get() ``` which will get you all of the regions. 

Usually though, we need to process the regions, cause if they contain items, we need to get them from the DB.
So we need to do a ``` $regions->processRegions()->get() ``` in order to go to the DB and fetch the actual items.

Remember, the actual item will be in the "item" property.

Sometimes, we know how our layout is going to be and for example, we know that we only need to process 1 or 2 regions,
thus we filter for improved performance. ``` $regions->processRegions(['featured', 'slider'])->get() ```. 
This will only process the 2 regions mentioned in the array.

There can be a case where we only need one region. We can filter it down and the result will not be an array,
but the actual object (like doing a $regions[0]).
```
$region = $regions->filter('frontPage')
    ->region('slider')
    ->process(null, false, true)
    ->get()
```

This will fetch the slider region, processed as a single object.

### Process multiple regions
```
$regions = $editableRegions->filter(['name' => 'frontPage'])
    ->process()
    ->get(true);
```

which will return a named array of the results
```
[
    'frontPage' => [
            'region 1' => [...],
            'region 2' => [...],
        ]
]

```

### A word of caution
Though editable regions can be extremely powerful and handy to use, try not to overdo it. Giving total control
to the user can break the design or possibly make that layout slow because of all the stuff the user is adding

# Layouts
typical config
```
    [
        'label' => 'Categories',
        'varName' => 'category',
        'view' => 'articles.index',
        'beforeRender' => '', //class that will be executed before render
        'settings' => [
            [
                'varName' => 'bannerImage',
                'label' => 'Banner Image',
                'type' => 'image',
                'options' => null
            ],
        ],
        'area' => ['pages.categories'] //only show in these areas
    ]
```

Layouts support settings that can be filled by the user and they are saved
in the item settings field. You can use them to customize your layouts.

## API
First of you can access the item layout via the ```$article->settings['Layout']``` property.
Then, you can find the layout using the layout manager
```  
$layout = LayoutManager::registry($article->settings['Layout']['id'], true);
```
The second parameter refers to whether you choose to process the layout or not,
doing so, it wil fetch all layout data (execute classes, fetch items, etc).

If you have set the 'beforeRender' option, then you will get a handler class back
which you can use to get the result of your "beforeRender" class. For example,
we have a ```beforeRender``` class ```PageById``` which grabs a single id set in
the layout settings and fetches that page as an object. You can gain access to that
object simply by doing this :

```
$article->custom = $layout['handler']->handle($request, $article, $pageService, $filters);
```

The parameters in the handle class are just an example of how you can pass whatever
you feel like to that handler class

You can add a new layout at runtime by calling the ```register``` method like so
```
LayoutManager::register($layoutArray)
```
