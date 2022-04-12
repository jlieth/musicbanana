<script lang="ts">
    export let name: String

    import { createInertiaApp } from "@inertiajs/inertia-svelte"

    createInertiaApp({
        resolve: (name) => require(`./Pages/${name}.svelte`),
        setup({ el, App, props }) {
            new App({ target: el, props })
        },
    })
</script>

<div id="app">
    <h1>hi {name}</h1>
</div>

<style lang="postcss" global>
    @tailwind base;
    @tailwind components;
    @tailwind utilities;
</style>
