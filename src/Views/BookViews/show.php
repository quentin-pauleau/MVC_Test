<?php
/**
 * @var \Src\Records\Book $book
 */

?>

<main>
	<div id="book-<?= $book->id ?>" class="container">
		<h1><?= $book->title ?></h1>
		<dl>
			<dt>Author :</dt>
			<dd><?= $book->author->getFullName() ?></dd>

			<dt>Publication date :</dt>
			<dd><?= $book->publicationDate->format('Y-m-d') ?></dd>
		</dl>

		<p><?= $book->description ?></p>
	</div>

	<section id="comments">
		<h2>Comments</h2>

		
	</section>
</main>