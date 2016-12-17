<?php

namespace Mcms\FrontEnd\Helpers;


use Illuminate\Support\Collection;
use Session;

/**
 * Class RecentlyViewed
 * @package FrontEnd\Helpers
 */
class RecentlyViewed
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var integer
     */
    protected $limit;
    /**
     * @var Session
     */
    public $session;

    /**
     * @var string
     */
    protected $rotateDirectionWhenLimitReached = 'last';
    /**
     * @var bool
     */
    protected $rotateWhenLimitReached = true; //when limit reached

    /**
     * Pass a unique name for the collection
     *
     * RecentlyViewed constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = "recent.{$name}";
        $this->session = session();
        return $this;
    }

    /**
     * Set a limit for the number of elements in the collection
     *
     * @param integer $limit
     */
    public function limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Which way should we rotate to, defaults to last but it can be first also
     *
     * @param string|null $way
     */
    public function rotate($way)
    {
        if ( is_null($way)){
            $this->rotateWhenLimitReached = false;
        }

        $this->rotateDirectionWhenLimitReached = $way;

        return $this;
    }

    /**
     * Add something to the collection. If you have set the limit it will stop there
     * If you set the rotation boolean it will rotate the list based on the $rotateDirectionWhenLimitReached
     * which defaults to last
     *
     * @param array|Collection $item
     * @param null|string $uniqueKey
     * @return $this
     */
    public function put($item, $uniqueKey = null)
    {
        if ( ! is_null($uniqueKey) && $this->exists($uniqueKey, $item[$uniqueKey])) {
            return $this;
        }

        if (count($this->session->get($this->name)) == $this->limit){
            if ( ! $this->rotateWhenLimitReached){
                return $this;
            }

            $items = $this->toCollection();
            if ($this->rotateDirectionWhenLimitReached === 'last'){
                $items->pop();
            } else { //remove from start
                $items->splice(0,1);
            }
            //reset the session value
            $this->session->put($this->name, $items->all());
        }

        $this->session->push($this->name, $item);

        return $this;
    }

    /**
     * Returns the session values. Defaults to collection, override it to get the natural data
     *
     * @param bool $raw
     * @return Collection|mixed
     */
    public function get($raw = false)
    {
        if (is_null($this->session->get($this->name))){
            return ($raw) ? [] : new Collection();
        }

        return ($raw) ? $this->session->get($this->name) : $this->toCollection();
    }

    /**
     * Convert the session data to collection
     *
     * @return Collection
     */
    public function toCollection()
    {
        $collection = new Collection();
        foreach ($this->get(true) as $item) {
            $collection->push($item);
        }

        return $collection;
    }

    /**
     * Check if an element exists in our collection
     *
     * @param $key
     * @param $value
     * @return mixed
     */
    public function exists($key, $value)
    {
        $items = $this->toCollection();
        return $items->where($key, $value)->first();
    }

    /**
     * Check if an item is in our collection
     *
     * @param $key
     * @param $value
     * @return bool
     */
    public function has($key, $value)
    {
        return ($this->exists($key, $value)) ? true : false;
    }

    /**
     * Reset the session
     */
    public function forget()
    {
        $this->session->forget($this->name);

        return $this;
    }
}