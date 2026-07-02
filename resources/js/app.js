

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

if (!window.Alpine) {
    window.Alpine = Alpine;
    Alpine.plugin(collapse);
    Alpine.start();
} else {
    window.Alpine.plugin(collapse);
}

