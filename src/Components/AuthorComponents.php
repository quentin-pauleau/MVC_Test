<?php

use Modules\HTMLElement\Components\Component;
use Modules\HTMLElement\Components\DataDisplay\Avatar;
use Modules\HTMLElement\Components\DataDisplay\ItemListRow;
use Src\Records\author;

use Modules\HTMLElement\Components\DataDisplay\Card;
use Modules\HTMLElement\Components\DataDisplay\ComponentSize;
use Modules\HTMLElement\Components\DataDisplay\ItemList;

final abstract class AuthorComponents
{
	public static function Card(
		Author $author,
		?string $id = null,
		ComponentSize $size = ComponentSize::DEFAULT,
		bool $hasBorder = true
	): Card {
		return new Card(
			$id ?? "author-card-{$author->id}",
			$size,
			$hasBorder,
			[
				"<h3>{$author->GetFullName()}</h3>",
				"<span>{$author->birthDate->format("Y-m-d")}</span>",
				"<p>{$author->bio}</p>",
			]
		);
	}

	public static function List(
		array $authors,
		?string $id = null,
	): ItemList {
		return new ItemList(
			$id,
			array_map(fn($author): ItemListRow => self::ListRow($author), $authors)
		);
	}

	public static function ListRow(
		Author $author,
		?string $id = null,
	): ItemListRow {
		return new ItemListRow(
			$id ?? "author-list-item-{$author->id}",
			[
				self::Avatar($author),
				"<h3>{$author->GetFullName()}</h3>",
				"<span>{$author->birthDate->format("Y-m-d")}</span>",
				"<p>{$author->bio}</p>",
			]
		);
	}


	public static function Avatar(
		Author $author,
		?string $id = null,
	): Avatar {
		return new Avatar(
			$id ?? "author-icon-{$author->id}",
			$author->profilePictureUrl
		);
	}
}