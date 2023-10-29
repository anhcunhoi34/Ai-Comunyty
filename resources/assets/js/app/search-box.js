`use strict`;

export class SearchBox extends HTMLElement {
    constructor() {
        super();

        this.input = this.querySelector('input');
    }

    connectedCallback() {
        window.addEventListener('keydown', (e) => {
            if (e.metaKey && e.key === 'k') {
                e.preventDefault();
                this.input.focus();
            } else if (e.key === 'Escape') {
                this.input.blur();
            }
        });
    }
}