import './bootstrap';
import './search';
import './filter';
import 'flowbite';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse'

window.Alpine = Alpine;
Alpine.plugin(collapse)

Alpine.start();