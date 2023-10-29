`use strict`;

import Alpine from 'alpinejs';

import { initState } from './state.js';
import { SearchBox } from './search-box.js';

import { homeView } from './home.js';
import { aiView } from './ai.js';
import { listView } from './list.js';
import { documentView } from './document.js';
import { billingView } from './billing.js';

import { Checkout } from './checkout.js';
import { accountView } from './account.js';

initState();
customElements.define('search-box', SearchBox);
window.checkout = new Checkout();

listView();
homeView();
aiView();
documentView();
billingView();
accountView();

Alpine.start();