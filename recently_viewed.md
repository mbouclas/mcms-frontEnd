# Recently viewed items helper
The idea is that you can store all sorts of stuff to this and it keeps it a session. Can be extended
to store stuff in a different location like the DB.

# Example of usage
```
    $recent = new RecentlyViewed('pages');
    $recent
        ->limit(3) //limit the collection to 3 items
        ->rotate('first') //remove the 1st item of the collection when the limit is reached
        ->forget();//empty any previous values
    $recent
        ->put(Page::find(299),'id')//add an item and make sure it is unique by id
        ->put(Page::find(300),'id')
        ->put(Page::find(302),'id')
        ->put(Page::find(301),'id');


    print_r($recent->get()->pluck('id')->toArray()); //get collection and pluck by id
```
# API
### Constructor
`$recent = new RecentlyViewed('pages');`
You need to pass a unique name as you can store stuff from different sources in there.

### put($item, $uniqueKey = null)
The `$item` can be an array or a collection. It could be a string but that will complicate querying the data.
The `$uniqueKey` has to be a key that we will check the uniqueness of the data against, for example `id`
If you don't pass a `$uniqueKey` the same element can be added more than once.
 
### get($raw = false)
Return the items of the session. By default we get back a collection to make handling easier, but
you can set `$raw` to true and you will get your raw data.

### exists($key, $value)
Check a key/value pair against the stored data. Basically, this can be used to check for the uniqueness
of an item inside our collection. If it exists, you get the item

### has($key, $value)
Same as `exists` only that it returns a boolean

### forget()
Empty the current collection

### limit($limit)
Set a limit to the number of items the collection holds. If you also set the the `rotate()` then
excess items will be replaced by new ones. Otherwise nothing new is added.

### rotate($way)
Define the rotation type. Can be first or last. Depending on the type, items will be removed once
we reach the limit in the collection. If way is set to null, then item rotation is disabled.

