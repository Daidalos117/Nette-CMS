<?php

/**
 * This file is part of the Nette Framework (http://nette.org)
 * Copyright (c) 2004 David Grudl (http://davidgrudl.com)
 */

namespace Nette\Application;

use Nette;


/**
 * The bi-directional router.
 */
interface IRouter
{
	/** only matching route */
	const ONE_WAY = 1;

	/** HTTPS route */
	const SECURED = 2;

	/**
	 * Maps HTTP request to a Request object.
	 * @return Request|NULL
	 */
	function match(Nette\Http\IRequest $httpRequest);

	/**
	 * Constructs absolute URL from Request object.
	 * @return string|NULL
	 */
	function constructUrl(Request $appRequest, Nette\Http\Url $refUrl);

}
