'use strict';

import Alpine from 'alpinejs';
import { api } from './api';
import { getPlanList } from './helpers';

export function settingsView() {
    Alpine.data('settings', () => ({
        required: [],
        isProcessing: false,
        isSubmitable: false,
        plans: [],
        plansFetched: false,

        init() {
            this.$refs.form.querySelectorAll('[required]').forEach((element) => {
                this.required.push(element);

                element.addEventListener('input', () => this.checkIsSubmitable());
            });

            this.checkIsSubmitable();

            getPlanList()
                .then(plans => {
                    this.plans = plans;
                    this.plansFetched = true;
                });
        },

        submit() {
            if (!this.isSubmitable || this.isProcessing) {
                return;
            }

            this.isProcessing = true;

            let data = new FormData(this.$refs.form);
            this.$refs.form.querySelectorAll('input[type="checkbox"]').forEach((element) => {
                data.append(element.name, element.checked ? '1' : '0');
            });

            api.post(`/options${this.$refs.form.dataset.path || ''}`, data, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })
                .then(response => {
                    this.isProcessing = false;

                    window.toast
                        .show('Changes saved successfully', 'ti ti-square-rounded-check-filled')
                })
                .catch(error => {
                    let msg = 'Couldn\'t save changes';

                    if (error.response && error.response.data.message) {
                        msg = error.response.data.message;
                    }

                    this.isProcessing = false;
                    window.toast
                        .show(msg, 'ti ti-square-rounded-x-filled')
                });
        },

        checkIsSubmitable() {
            for (let i = 0; i < this.required.length; i++) {
                const el = this.required[i];

                if (!el.value) {
                    this.isSubmitable = false;
                    return;
                }
            }

            this.isSubmitable = true;
        }
    }))
}