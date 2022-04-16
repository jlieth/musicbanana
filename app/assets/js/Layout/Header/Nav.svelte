<script lang="ts">
    const routes = require("@/routes.json")
    import Routing from "@public/bundles/fosjsrouting/js/router.min.js"
    import Button from "@/Layout/Header/Button.svelte"

    Routing.setRoutingData(routes);

    export let open = false
</script>

<nav class:hide={!open}>
    <Button class="ml-2 md:ml-0">
        <a href="/">Button</a>
    </Button>
    <Button class="ml-2 md:ml-0">
        <a href="{Routing.generate("app_login", true)}">Login</a>
    </Button>
</nav>

<style lang="postcss">
    nav { @apply flex flex-row items-center pr-3; }

    @media screen(md) {
        nav { @apply w-full flex-col items-stretch pr-0; }
        nav.hide { @apply hidden; }
    }
</style>
