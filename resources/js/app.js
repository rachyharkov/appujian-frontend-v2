import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import bootstrapBundle from "bootstrap/dist/js/bootstrap.bundle"
import AuthenticatedLayout from '@/Shared/Layout/AuthenticatedLayout.vue'
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import './utils/components/sidebar.js'
import './utils/components/dark.js'
import '../../public/assets/static/js/components/dark.js'
import '../../public/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js'
import '../../public/assets/static/js/initTheme.js'

window.bootstrap = bootstrapBundle

createInertiaApp({
    resolve: async name => {
        const pages = await import.meta.glob('./Pages/**/*.vue', { eager: true })
        const page = pages[`./Pages/${name}.vue`]

        if (page && page.default) {
            page.default.layout = page.default.layout || AuthenticatedLayout
        }
        return page
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(VueSweetalert2)
            .mixin({ methods: { route } })
            .mount(el)
    },
    progress: {
        showSpinner: true,
        color: '#435ebe',
        includeCSS: true
    }
})
