document.addEventListener('DOMContentLoaded', () => {
	// Ajax Search function
	function ajaxSearch() {
		const searchInput = document.querySelector('.search-form__input');
		const searchResults = document.querySelector('.ajax-search');

		searchInput.addEventListener('keyup', function () {
			let searchValue = this.value;

			// character count
			if (searchValue.length > 0) {
				fetch('/wp-admin/admin-ajax.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded;',
					},
					body: `action=ajax_search&term=${encodeURIComponent(searchValue)}`,
				})
					.then(response => response.text())
					.then(results => {
						searchResults.style.display = 'block';
						searchResults.innerHTML = results;
						setTimeout(() => {
							searchResults.style.opacity = 1;
						}, 200); // Fade in effect
					});
			} else {
				searchResults.style.opacity = 0;
				setTimeout(() => {
					searchResults.style.display = 'none';
				}, 200); // Fade out effect
			}
		});

		// Closing a search when clicking outside of it
		document.addEventListener('click', function (event) {
			if (
				!event.target.closest('.ajax-search') &&
				!event.target.closest('.search-form__input')
			) {
				searchResults.style.opacity = 0;
				setTimeout(() => {
					searchResults.style.display = 'none';
				}, 200); // Fade out effect
			}
		});
	}
	ajaxSearch();
});
