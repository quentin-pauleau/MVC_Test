<?php

use Modules\HTMLElement\Components\Component;
use Src\Records\Book;

use Modules\HTMLElement\Components\DataDisplay\Card;
use Modules\HTMLElement\Components\DataDisplay\ComponentSize;
use Modules\HTMLElement\Components\DataDisplay\ItemList;
use Modules\HTMLElement\Components\DataDisplay\ItemListRow;

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
				"<span>{$book->publicationDate->format("Y-m-d")}</span>",
				"<p>{$book->description}</p>",
			]
		);
	}

	public static function List(
		array $books,
		?string $id = null,
	): ItemList {
		return new ItemList(
			$id,
			array_map(fn($book): ItemListRow => self::ListRow($book), $books)
		);
	}

	public static function ListRow(
		Book $book,
		?string $id = null
	): ItemListRow {
		return new ItemListRow(
			$id ?? "book-list-item-{$book->id}",
			[
				"<h3>{$book->title}</h3>",
				"<span>{$book->publicationDate->format("Y-m-d")}</span>",
				"<p>{$book->description}</p>",
			]
		);
	}
}