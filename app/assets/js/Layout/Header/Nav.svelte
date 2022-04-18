<script lang="ts">
    const routes = require("@/routes.json")
    import { inertia } from "@inertiajs/inertia-svelte"
    import Routing from "@public/bundles/fosjsrouting/js/router.min.js"
    import Button from "@/Layout/Header/Button.svelte"

    export let open = false

    Routing.setRoutingData(routes)
    let urls = {
        "index": Routing.generate("index", true),
        "login": Routing.generate("login", true),
    }

</script>

<nav class:hide={!open}>
    <Button class="ml-2 md:ml-0">
        <a href="{urls["index"]}" use:inertia>Home</a>
    </Button>
    <Button class="ml-2 md:ml-0">
        <a href="{urls["login"]}" use:inertia>Login</a>
    </Button>
</nav>

<style lang="postcss">
    nav { @apply flex flex-row items-center pr-3; }

    @media screen(md) {
        nav { @apply w-full flex-col items-stretch pr-0; }
        nav.hide { @apply hidden; }
    }
</style>
