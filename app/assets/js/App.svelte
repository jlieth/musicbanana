<script lang="ts">
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
</script>

<style lang="postcss" global>
    @tailwind base;
    @tailwind components;
    @tailwind utilities;

    body {
        @apply bg-gray-300;
    }

    a, a:focus, a:hover {
        text-decoration: none;
    }

    a {
        color: #333;
    }
</style>
