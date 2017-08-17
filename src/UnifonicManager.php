<?php

namespace Zizou86\Unifonic;


class UnifonicManager
{

    /**
     * @var App
     */
    public $app;


    /**
     * UnifonicManager constructor.
     */
    public function __construct()
    {
        $this->with('default');
    }


    /**
     * Load the custom AppSid interface.
     *
     * @param AppContract $appsid
     * @return $this
     */
    public function withCustomApp(AppContract $app)
    {
        $this->app = $app;
        return $this;
    }



    /**
     * Create new app instance with given credentials.
     *
     * @param string $appsid
     * @param array $urls
     * @return $this
     */
    public function with($appskey, array $urls = null)
    {
        $urls = $urls ?: config('unifonic.urls');
        $appsid = config('unifonic.appsid.'.$appskey);

        $this->app = new App($appsid, $urls);

        return $this;
    }


    /**
     * Dynamically call methods on the app.
     *
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (!method_exists($this->app, $method)) {
            abort(500, "Method $method does not exist");
        }
        return call_user_func_array([$this->app, $method], $parameters);
    }

}