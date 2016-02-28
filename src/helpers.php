<?php

	/**
	 * Get the available container instance.
	 *
	 * @param  string  $make
	 * @param  array   $parameters
	 * @return mixed|\Core\Application
	 */
	function app($make = null, $parameters = [])
	{
		if (is_null($make)) {
			return \Illuminate\Container\Container::getInstance();
		}

		return \Illuminate\Container\Container::getInstance()->make($make, $parameters);
	}

	/**
	 * Translate a string
	 * @param string $message
	 * @param array $parameters
	 * @param string $locale
	 * @param bool $onlyReplacements
	 * @return string
	 */
	function trans($message, $parameters = array(), $locale = null, $onlyReplacements = false)
	{
		return \Core\Localization\Translator::getInstance()->trans($message, $parameters, null, $locale, $onlyReplacements);
	}

	/**
	 * Dump the passed variables and continue
	 */
	function dd()
	{
		array_map(function ($x) {
			(new \Illuminate\Support\Debug\Dumper())->dump($x);
		}, func_get_args());
	}

	/**
	 * Dump the passed variables and exit
	 */
	function de()
	{
		array_map(function ($x) {
			(new \Illuminate\Support\Debug\Dumper())->dump($x);
		}, func_get_args());
		exit(1);
	}

	/**
	 * Get a faker
	 * @return \Faker\Generator
	 */
	function faker()
	{
		return (new \Faker\Generator());
	}

	/**
	 * Check if value is int
	 * @param $var
	 * @return bool
	 */
	function is_int_($var)
	{
		return is_numeric($var) && preg_match('@^\d+$@', $var);
	}

	/**
	 * Get instance of former
	 * @return \Former\Former
	 */
	function former()
	{
		app()->register(\Former\FormerServiceProvider::class);

		return app('former');
	}

	/**
	 * Encrypt user password
	 * @param  string $value
	 * @return string
	 */
	function bcrypt($value)
	{
		return (new Illuminate\Hashing\BcryptHasher())->make($value);
	}

	/**
     * Generate a CSRF token form field.
     *
     * @return string
     */
    function csrf_field()
    {
        return new \Illuminate\Support\HtmlString('<input type="hidden" name="_token" value="'.csrf_token().'">');
    }

    /**
     * Get the CSRF token value.
     *
     * @return string
     */
    function csrf_token()
    {
    	return \Core\Http\Client\Session::getInstance()->token();
    }

    /**
     * Get an instance of the redirector.
     *
     * @param  string|null  $to
     * @param  int     $status
     * @param  array   $headers
     * @param  bool    $secure
     * @return \Laravel\Lumen\Http\Redirector|\Illuminate\Http\RedirectResponse
     */
    function redirect($to = null, $status = 302, $headers = [], $secure = null)
    {
        $redirector = new \Core\Routing\Redirector(app());

        if (is_null($to)) {
            return $redirector;
        }

        return $redirector->to($to, $status, $headers, $secure);
    }