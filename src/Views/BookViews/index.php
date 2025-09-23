<?php

use Modules\HTMLElement\Components\DataDisplay\Button;
/**
 * @var Src\Records\Book[] $books
 */
$books

?>


<main>
	<div class="container">
		<h1>Books</h1>
		<span><?= count($books) ?> books found.</span>

		<div>
			<?php foreach ($books as $book) : ?>
				<article>
					<?= BookComponents::Card($book) ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</main>