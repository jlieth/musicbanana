import { createInertiaApp } from "@inertiajs/inertia-svelte"
import { InertiaProgress } from "@inertiajs/progress"

InertiaProgress.init()

createInertiaApp({
	resolve: (name: String) => require(`./Pages/${name}.svelte`),
	setup({ el, App, props }) {
		console.log(props)
		new App({ target: el, props })
  	},
})
