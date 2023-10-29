'use strict';

import axios from "axios";

const ins = axios.create({
    baseURL: '/admin/api',
    headers: {
        'Content-Type': 'application/json'
    }
});

ins.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('jwt');

        if (token) {
            config.headers['Authorization'] = `Bearer ${token}`;
        }

        return config;
    }, (error) => {
        Promise.reject(error);
    }
);

export const api = ins;
