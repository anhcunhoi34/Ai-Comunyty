'use strict';

import Alpine from 'alpinejs';
import { api } from './api';
import { debounce } from '../debounce';
import { getCategoryList } from './helpers';

export function homeView() {
    Alpine.data('home', () => ({
        state: 'initial',
        isLoading: false,
        categories: [],
        params: {
            query: null,
            category: null,
            type: null
        },

        presets: [],
        cursor: null,
        hasMore: true,
        categoriesLoaded: false,
        presetsLoaded: false,

        init() {
            for (const key in this.params) {
                this.$watch(`params.${key}`, debounce(
                    value => this.retrieveResources(true),
                    200
                ))
            }

            this.loadMore();
            this.getCategories();
            this.retrieveResources();
        },

        getCategories() {
            getCategoryList()
                .then(categories => {
                    this.categories = categories;
                    this.categoriesLoaded = true;
                });
        },

        toggleFilter(type, id, el = null) {
            let filter = this.filters.find(
                filter =>
                    filter.type === type && filter.id === id
            );

            if (filter) {
                this.filters = this.filters.filter(
                    filter =>
                        filter.type !== type || filter.id !== id
                );
            } else {
                this.filters.push({
                    type: type,
                    id: id
                });
            }

            if (el) {
                el.dataset.state = filter ? 'inactive' : 'active';
            }
        },

        retrieveResources(reset = false) {
            this.isLoading = true;
            let limit = 24;

            let params = {
                limit: limit
            };

            for (const key in this.params) {
                if (this.params[key]) {
                    params[key] = this.params[key];
                }
            }

            if (!reset && this.cursor) {
                params.starting_after = this.cursor;
            }

            api
                .get('/presets', { params })
                .then(response => {
                    this.presetsLoaded = true;
                    this.state = 'loaded';
                    this.presets = reset
                        ? response.data.data
                        : this.presets.concat(response.data.data);

                    if (this.presets.length > 0) {
                        this.cursor = this.presets[this.presets.length - 1].id;
                    } else {
                        this.state = 'empty';
                    }

                    this.isLoading = false;
                    this.hasMore = response.data.data.length >= limit;
                });
        },

        resetFilters() {
            // Reset all params except query
            for (const key in this.params) {
                if (key !== 'query') {
                    this.params[key] = null;
                }
            }
        },

        loadMore() {
            window.addEventListener('scroll', () => {
                if (
                    this.hasMore
                    && !this.isLoading
                    && (window.innerHeight + window.scrollY + 500) >= document.documentElement.scrollHeight
                ) {
                    this.retrieveResources();
                }
            });
        }
    }));
}