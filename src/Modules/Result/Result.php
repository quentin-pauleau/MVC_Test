<?php
namespace Modules\Result;


/**
 * 
 * @template Res Result type
 * @template Err Error type
 */
final readonly class Result
{
	/**
	 * Summary of __construct
	 * @param Res $result
	 * @param Err $error
	 */
	private function __construct(
		/**
		 * @var Res|null $result
		 */
		private mixed $result = null,

		/**
		 * @var Err|null $error
		 */
		private mixed $error = null
	) {}

	/**
	 * @param mixed $result
	 * @return Result<Res, null>
	 */
	public static function Success(mixed $result): self { return new self ($result, null); }

	/**
	 * @param mixed $error
	 * @return self<null, Err>
	 */
	public static function Fail(mixed $error): self { return new self (null, $error); }


	/**
	 * 
	 * @param callable $callback
	 * @return Result<Res|null, Err|null>
	 */
	public static function FromCallable(callable $callback): self
	{
		try
		{
			return self::Success($callback());
		}
		catch (\Throwable $throwable)
		{
			return self::Fail($throwable);
		}
	}

	/**
	 * @throws \Exception
	 * @return Res
	 */
	public function GetResult(): mixed
	{
		if ($this->IsSuccessful())
			return $this->result;
		
		throw new \Exception("Trying to get result from an invalid result");
	}

	/**
	 * @throws \Exception
	 * @return Err
	 */
	public function GetError(): mixed
	{
		if ($this->IsSuccessful())
			throw new \Exception("Trying to get error from a valid result");

		return $this->error;
	}

	/**
	 * @return bool
	 */
	public function IsSuccessful(): bool
	{
		return $this->error === null;
	}

	public function IsFailure(): bool
	{
		return !$this->IsSuccessful();
	}
}