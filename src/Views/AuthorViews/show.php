<?php
/**
 * @var \Src\Records\Author $author
 */

?>

<main>
	<div id="author-<?= $author->id ?>" class="container">
		<h1><?= $author->getFullName() ?></h1>
		<dl>
			<dt>Publication date :</dt>
			<dd><?= $book->publicationDate->format('Y-m-d') ?></dd>
		</dl>

		<p><?= $author->bio ?></p>

		<?= BookComponents::List($author->books) ?>
	</div>
</main>