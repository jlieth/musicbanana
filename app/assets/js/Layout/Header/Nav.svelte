<script lang="ts">
    import { inertia } from "@inertiajs/inertia-svelte"
    import router from "@/router"
    import Button from "@/Layout/Header/Button.svelte"
    import SearchBar from "@/Layout/Header/SearchBar.svelte"

    export let open = false
    $: hide = !open

    let urls = {
        "index": router.generate("index", true),
        "login": router.generate("login", true),
    }

</script>

<nav class:hide>
    <SearchBar />

    <Button>
        <a href="{urls["index"]}" use:inertia>Home</a>
    </Button>
    <Button>
        <a href="{urls["login"]}" use:inertia>Login</a>
    </Button>
</nav>

<style lang="postcss">
    nav {
        @apply flex flex-row items-center gap-3;
        @apply md:w-full md:flex-col md:items-stretch md:gap-1.5 md:mt-3 md:pr-0;
    }

    nav.hide {
        @apply md:hidden;
    }
</style>
