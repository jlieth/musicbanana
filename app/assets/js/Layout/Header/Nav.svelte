<script lang="ts">
    import { inertia } from "@inertiajs/inertia-svelte"
    import router from "@/router"
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

    <a href="{urls["index"]}" use:inertia>Home</a>
    <a href="{urls["login"]}" use:inertia>Login</a>
</nav>

<style lang="postcss">
    nav {
        @apply h-full flex flex-row items-center gap-3;
        @apply md:w-full md:flex-col md:items-stretch md:gap-1.5 md:mt-3 md:pr-0;
    }

    nav.hide {
        @apply md:hidden;
    }

    a {
        @apply h-full flex items-center md:w-full p-3 text-gray-200;
        @apply border-b-4 border-transparent md:border-b-0 md:border-l-4;
        @apply transition;
    }

    a:hover {
        @apply bg-header-900;
        @apply border-highlight-500;
    }
</style>
