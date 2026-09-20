document.addEventListener('DOMContentLoaded', function () {
	var menu = document.querySelector('.menu');
	var sidebar = document.querySelector('.sidebar');

	if (menu && sidebar) {
		menu.addEventListener('click', function () {
			sidebar.classList.toggle('open');
		});
	}

	var status = document.querySelector('.led-status');

	window.addEventListener('load', function () {
		if (status) status.classList.add('ready');
	});

	if (document.readyState === 'complete' && status) {
		status.classList.add('ready');
	}

	var filters = document.querySelectorAll('.filter');
	var links = document.querySelectorAll('[data-category-link]');
	var rows = document.querySelectorAll('.post-row');
	var input = document.getElementById('journal-search');
	var none = document.getElementById('no-results');
	var shell = document.getElementById('search-shell');
	var active = 'all';

	function render() {
		var term = input ? input.value.trim().toLowerCase() : '';
		if (shell) shell.classList.toggle('has-value', !!term);

		var count = 0;
		rows.forEach(function (row) {
			var cat = row.dataset.category || '';
			var text = (row.dataset.search || '').toLowerCase();
			var show = (active === 'all' || cat === active) && (!term || text.indexOf(term) > -1);

			row.classList.toggle('hide', !show);
			if (show) count++;
		});

		if (none) {
			none.classList.toggle('show', rows.length > 0 && count === 0);
		}
	}

	function pick(cat) {
		active = cat;

		filters.forEach(function (item) {
			item.classList.toggle('active', item.dataset.filter === cat);
		});

		links.forEach(function (item) {
			item.classList.toggle('active', item.dataset.categoryLink === cat);
		});

		render();
	}

	filters.forEach(function (button) {
		button.addEventListener('click', function () {
			pick(button.dataset.filter);
		});
	});

	links.forEach(function (link) {
		link.addEventListener('click', function (e) {
			if (location.pathname === link.pathname && location.hash.indexOf('#projects') === 0) {
				e.preventDefault();
				pick(link.dataset.categoryLink);
				var target = document.getElementById('projects');
				if (target) {
					target.scrollIntoView({ behavior: 'smooth' });
				}
			}
		});
	});

	if (input) {
		input.addEventListener('input', render);

		document.addEventListener('keydown', function (e) {
			if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
				e.preventDefault();
				input.focus();
			}
		});
	}

	function clock() {
		var el = document.getElementById('footer-beijing-time');
		if (!el) return;

		var tz = el.dataset.tz || 'Asia/Shanghai';
		var label = el.dataset.label || 'CST';

		var parts = new Intl.DateTimeFormat('en-CA', {
			timeZone: tz,
			year: 'numeric',
			month: '2-digit',
			day: '2-digit',
			hour: '2-digit',
			minute: '2-digit',
			second: '2-digit',
			hour12: false
		}).formatToParts(new Date()).reduce(function (obj, part) {
			obj[part.type] = part.value;
			return obj;
		}, {});

		el.textContent = label + ' / ' + parts.year + '.' + parts.month + '.' + parts.day + ' ' + parts.hour + ':' + parts.minute + ':' + parts.second;
	}

	clock();
	setInterval(clock, 1000);
});
