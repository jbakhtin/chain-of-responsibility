<?php

interface Handler
{
	public function setNext(Handler $handler): Handler;

	public function handle(array $request): ? string;
}

/**
 * The default chaining behavior can be implemented inside a base handler class.
 */
abstract class AbstractHandler implements Handler
{
	/**
	 * @var Handler
	 */
	private $nextHandler;

	public function setNext(Handler $handler): Handler
	{
		$this->nextHandler = $handler;
		return $handler;
	}

	public function handle(array $request): ?string
	{
		if ($this->nextHandler)
			return $this->nextHandler->handle($request);

		return null;
	}
}

/**
 * All Concrete Handlers either handle a request or pass it to the next handler
 * in the chain.
 */
class PhoneHandler extends AbstractHandler
{
	public function handle(array $params): ?string
	{
		$item = current($params);
		next($params);
		echo $item;
		return parent::handle($params);

		if ($item !== "phone") {
			return "Phone is not valid" . "<br>";
		} else {
			return parent::handle($params);
		}
	}
}

class FilterHandler extends AbstractHandler
{
	public function handle(array $params): ?string
	{
		$item = current($params);
		next($params);

		echo $item;
		return parent::handle($params);

		if ($item !== "carrier") {
			return "Filter is not valid" . ".<br>";

		} elseif ($item === "id") {

		} else {
			return parent::handle($params);
		}
	}
}

/**
 * The client code is usually suited to work with a single handler. In most
 * cases, it is not even aware that the handler is part of a chain.
 */
function clientCode(Handler $handler)
{
	$result = $handler->handle(["phone", "id"]);
	if ($result) {
		echo "true <br>";
	} else {
		echo "false <br>";
	}
}

/**
 * The other part of the client code constructs the actual chain.
 */
$phone = new PhoneHandler();
$carrier = new FilterHandler();

$phone->setNext($carrier);

clientCode($phone);