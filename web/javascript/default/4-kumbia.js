(function() {
    const KumbiaJS = {
        aAjax: function(eve) {
            eve.preventDefault();
            const mensaje = this.dataset.confirm;
            if (mensaje && !confirm(mensaje)) {
                return false;
            }
            const to = document.querySelector(this.dataset.ajax);
            console.log([to, this.href]);
            fetch(this.href)
                .then(response => response.text())
                .then(data => {
                    to.innerHTML = data;
                    console.log([to, this.href]);
                })
                .catch(error => console.error('Error:', error));
        },

        active: function() {
            const to = document.querySelector(this.dataset.active);
            to.classList.remove('active');
            this.classList.add('active');
        },

        alert: function() {
            alert(this.dataset.alert);
        },

        checkbox: function(el) {
            console.log(el);
            if (el.readOnly) el.checked = el.readOnly = false;
            else if (!el.checked) el.readOnly = el.indeterminate = true;
        },

        clone_content_append: function() {
            const params = this.dataset.clone_content_append.split(', ');
            const el = document.querySelector(params[0]);
            const to = document.querySelector(params[1]);
            console.log([params, el, to]);
            if (el instanceof HTMLTemplateElement) {
                const clone = document.importNode(el.content, true);
                to.appendChild(clone);
            }
        },

        confirm: function(eve) {
            if (!confirm(this.dataset.confirm)) {
                eve.preventDefault();
                eve.stopImmediatePropagation();
            }
        },

        effect: function(effect) {
            return function() {
                const to = document.querySelector(this.dataset[effect]);
                to.style[effect]();
            }
        },

        formAjax: function(eve) {
            eve.preventDefault();

            const form = this.closest('form');
            const url = form.getAttribute('action');
            let to;
            if (form.dataset.ajaxAppend) {
                to = document.querySelector(form.dataset.ajaxAppend);
            } else if (form.dataset.ajaxPrepend) {
                to = document.querySelector(form.dataset.ajaxPrepend);
            } else {
                to = document.querySelector(form.dataset.ajax);
            }
            const formData = new FormData(form);

            const buttons = form.querySelectorAll('[type="submit"]');
            buttons.forEach(button => button.setAttribute('disabled', 'disabled'));

            const btnName = this.getAttribute('name');
            if (btnName !== undefined) {
                const btnVal = this.value;
                formData.append(btnName, btnVal);
            }

            const fileData = form.querySelector('[type="file"]');
            if (fileData) {
                formData.append('file', fileData.files[0]);
            }

            fetch(url, {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    let mode;
                    if (form.dataset.ajaxAppend) {
                        to.innerHTML += data;
                        mode = 'append';
                    } else if (form.dataset.ajaxPrepend) {
                        to.innerHTML = data + to.innerHTML;
                        mode = 'prepend';
                    } else {
                        to.innerHTML = data;
                        mode = 'normal';
                    }
                    buttons.forEach(button => button.removeAttribute('disabled'));
                    console.log([to, url, mode]);
                })
                .catch(error => console.error('Error:', error));
        },

        live: function() {
            const to = document.querySelector(this.dataset.live);
            const href = this.dataset.href;
            fetch(href, {
                    method: 'POST',
                    body: JSON.stringify({
                        'keywords': this.value
                    })
                })
                .then(response => response.text())
                .then(data => {
                    to.innerHTML = data;
                    console.log([to, href, this.value]);
                })
                .catch(error => console.error('Error:', error));
        },

        remove: function() {
            const to = this.dataset.remove === 'parent' ? this.parentElement : document.querySelector(this.dataset.remove);
            to.remove();
        },

        selectAjax: function() {
            const to = document.querySelector(this.dataset.ajax);
            const href = this.dataset.href + this.value;
            fetch(href)
                .then(response => response.text())
                .then(data => {
                    to.innerHTML = data;
                    console.log([to, href]);
                })
                .catch(error => console.error('Error:', error));
        },

        selectRedirect: function() {
            const href = this.dataset.redirect + this.value;
            location.href = href;
        },

        selectToggle: function() {
            const to = document.querySelector(this.dataset.changeToggle);
            to.style.display = to.style.display === 'none' ? 'block' : 'none';
        },

        style: function() {
            const params = this.dataset.style.split(', ');
            const selector = params[0];
            const style = params[1];
            document.querySelector(selector).setAttribute('style', style);
        },

        toggleClass: function() {
            const params = this.dataset.toggleClass.split(', ');
            const className = params[0];
            const to = document.querySelector(params[1]);
            to.classList.toggle(className);
        },

        toggleDisplay: function() {
            const to = document.querySelector(this.dataset.toggleDisplay);
            if (window.getComputedStyle(to).display === 'none') {
                to.style.display = 'flex';
            } else {
                to.style.display = 'none';
            }
        },

        bind: function() {
            document.body.addEventListener('click', function(event) {
                const target = event.target;

                // Helper to find closest match
                const match = (selector) => target.closest(selector);
                let el;

                if (el = match('[data-active]')) KumbiaJS.active.call(el);
                if (el = match('a[data-ajax]')) KumbiaJS.aAjax.call(el);
                if (el = match('form[data-ajax] [type="submit"]')) KumbiaJS.formAjax.call(el);
                if (el = match('form[data-ajax_append] [type="submit"]')) KumbiaJS.formAjax.call(el);
                if (el = match('form[data-ajax_prepend] [type="submit"]')) KumbiaJS.formAjax.call(el);
                if (el = match('[type="checkbox"]')) KumbiaJS.checkbox.call(event); // checkbox uses event
                if (el = match('select[data-ajax]')) KumbiaJS.selectAjax.call(el);
                if (el = match('select[data-redirect]')) KumbiaJS.selectRedirect.call(el);
                if (el = match('select[data-change_toggle]')) KumbiaJS.selectToggle.call(el);
                if (el = match('[data-alert]')) KumbiaJS.alert.call(el);
                if (el = match('[data-click]')) KumbiaJS.effect('click').call(el);
                if (el = match('[data-clone_content_append]')) KumbiaJS.clone_content_append.call(el);
                if (el = match('[data-confirm]')) KumbiaJS.confirm.call(el);
                if (el = match('[data-fade_out]')) KumbiaJS.effect('fadeOut').call(el);
                if (el = match('[data-hide]')) KumbiaJS.effect('hide').call(el);
                if (el = match('[data-live]')) KumbiaJS.live.call(el);
                if (el = match('[data-remove]')) KumbiaJS.remove.call(el);
                if (el = match('[data-show]')) KumbiaJS.effect('show').call(el);
                if (el = match('[data-slide_down]')) KumbiaJS.effect('slideDown').call(el);
                if (el = match('[data-style]')) KumbiaJS.style.call(el);
                if (el = match('[data-toggle]')) KumbiaJS.effect('toggle').call(el);
                if (el = match('[data-toggle_class]')) KumbiaJS.toggleClass.call(el);
                if (el = match('[data-toggle_display]')) KumbiaJS.toggleDisplay.call(el);
            });
        }
    };
    KumbiaJS.bind();
})();