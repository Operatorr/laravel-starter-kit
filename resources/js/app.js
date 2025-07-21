// resources/js/app.js
import './bootstrap'

import { Livewire /*, Alpine*/ } from '../../vendor/livewire/livewire/dist/livewire.esm'
import Alpine from 'alpinejs'
import persist from '@alpinejs/persist'   // optional plugin

Alpine.plugin(persist)                   // register plugins *before* start()
window.Alpine = Alpine                  // exposes it to inline scripts if you need

// Alpine.start()                          // always start AFTER registrations
Livewire.start() 