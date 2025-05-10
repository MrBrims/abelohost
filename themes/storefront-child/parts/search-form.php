<search class="search-form">
	<form class="search-label" method="GET" role="search" action="<?php echo home_url('') ?>">
		<input class="search-form__input" value="<?php get_search_query() ?>" type="text" name="s" id="s" autocomplete="off" aria-label="Search bar" placeholder="Search" required minlength="3">
		<label class="search-form__icon">
			<input class="search-form__submit" type="submit" value="" aria-label="Search button">
		</label>
		<ul class="ajax-search"></ul>
	</form>
</search>