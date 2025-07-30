// resources/js/app.js
import './bootstrap'
import { themeChange } from 'theme-change'

import { Livewire /*, Alpine*/ } from '../../vendor/livewire/livewire/dist/livewire.esm'
import Alpine from 'alpinejs'
import persist from '@alpinejs/persist'   // optional plugin

import { marked }   from 'marked'
import DOMPurify    from 'dompurify'

window.md2html = (raw) => DOMPurify.sanitize(marked.parse(raw))

Alpine.plugin(persist)                   // register plugins *before* start()
window.Alpine = Alpine                  // exposes it to inline scripts if you need

// Initialize theme-change
themeChange()

// Alpine.start()                          // always start AFTER registrations
Livewire.start() 