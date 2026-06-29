import './bootstrap';
import Alpine from 'alpinejs';
import * as fabric from 'fabric';
import customizer from './customizer';

window.Alpine = Alpine;
window.fabric = fabric;

Alpine.data('customizer', customizer);
Alpine.start();
