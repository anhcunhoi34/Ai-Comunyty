'use strict';

export class ResourceId extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        this.setAttribute('title', "Click to copy")
        this.dataset.tippyPlacement = 'right';

        this.classList.add(
            'text-xs',
            'font-normal',
            'text-content-dimmed',
            'cursor-pointer',
            'select-none'
        );

        this.addEventListener('click', () => {
            navigator.clipboard.writeText(this.innerText)
                .then(() => {
                    window.toast
                        .show('Resource UUID is copied to the clipboard.', 'ti ti-copy');
                });

        });
    }
}