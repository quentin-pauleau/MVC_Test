




<main>
	<section class="container">
		<h1>Books</h1>
		<span><?= count($books) ?> books found.</span>

		<div>
			<?php foreach ($books as $book) : ?>
				<article>
					<?= BookComponents::Card($book) ?>
				</article>
			<?php endforeach; ?>
		</div>
	</section>
</main>