<?php

use Src\Records\User;

use Modules\HTMLElement\Components\DataDisplay\Card;
use Modules\HTMLElement\Components\DataDisplay\ComponentSize;
use Modules\HTMLElement\Components\DataDisplay\ItemList;
use Modules\HTMLElement\Components\DataDisplay\ItemListRow;


final abstract class UserComponents
{
	public static function Card(
		User $user,
		ComponentSize $size = ComponentSize::DEFAULT,
		bool $hasBorder = true
	): Card {
		return new Card(
			"user-card-{$user->id}",
			$size,
			$hasBorder,
			[
				"<h3>{$user->username}</h3>",
				"<span>{$user->createdAt->format("Y-m-d")}</span>",
				"<p>{$user->profileDescription}</p>",
			]
		);
	}

	public static function List(
		array $users,
		?string $id = null,
	): ItemList {
		return new ItemList(
			$id,
			array_map(fn($user): ItemListRow => self::ListRow($user), $users)
		);
	}

	public static function ListRow(
		User $user,
		?string $id = null
	): ItemListRow {
		return new ItemListRow(
			$id ?? "user-list-item-{$user->id}",
			[
				"<h3>{$user->username}</h3>",
				"<span>{$user->createdAt->format("Y-m-d")}</span>",
				"<p>{$user->profileDescription}</p>",
			]
		);
	}
}