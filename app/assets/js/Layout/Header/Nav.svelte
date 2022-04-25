<script lang="ts">
    import { inertia, page } from "@inertiajs/inertia-svelte"
    import router from "@/router"
    import ImgDropdown from "@/Layout/Header/ImgDropdown.svelte"
    import SearchBar from "@/Layout/Header/SearchBar.svelte"

    export let open = false
    $: hide = !open

    $: user = $page.props.user

    let urls = {
        "index": router.generate("index", true),
        "login": router.generate("login", true),
    }

</script>

<nav class:hide>
    <SearchBar class="mr-4 md:mr-0 md:mt-2" />

    <a href="{urls["index"]}" use:inertia>Home</a>
    <a href="/" use:inertia>Music</a>

    {#if !user}
        <a href="{urls["login"]}" use:inertia>Log In</a>
        <a href="/" use:inertia>Sign Up</a>
    {:else}
        <ImgDropdown class="ml-4 md:ml-0" />
    {/if}
</nav>

<style lang="postcss">
    nav {
        @apply h-full flex flex-row items-center;
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
