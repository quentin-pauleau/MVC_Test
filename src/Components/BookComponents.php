<?php

use Modules\HTMLElement\Components\Component;
use Src\Records\Book;

use Modules\HTMLElement\Components\DataDisplay\Card;
use Modules\HTMLElement\Components\DataDisplay\ComponentSize;

final abstract class BookComponents
{
	public static function Card(
		Book $book,
		ComponentSize $size = ComponentSize::DEFAULT,
		bool $hasBorder = true
	): Card {
		return new Card(
			"book-card-{$book->id}",
			$size,
			$hasBorder,
			[
				"<h3>{$book->title}</h3>",
				"<span>{$book->author}</span>",
				"<span>{$book->publicationDate->format("Y-m-d")}</span>",
				"<p>{$book->description}</p>",
			]
		);
	}
}