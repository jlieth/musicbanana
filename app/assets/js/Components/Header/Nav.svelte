<script lang="ts">
    import { UserIconOutline } from "@codewithshin/svelte-heroicons"
    import { inertia, page } from "@inertiajs/inertia-svelte"

    import router from "@/router"
    import Avatar from "@/Components/Header/Avatar.svelte"
    import SearchBar from "@/Components/Header/SearchBar.svelte"

    export let open = false
    $: hide = !open

    $: user = $page.props.user
</script>

<nav class:hide>
    <SearchBar class="mr-4 md:mr-0 md:mt-2" />

    <a href={router.indexUrl} use:inertia>Home</a>
    <a href="/" use:inertia>Music</a>
    <span class="divider"></span>

    {#if user}
        <Avatar class="md:ml-0" />
        <a href="/" use:inertia>Settings</a>
        <a href={router.logoutUrl} use:inertia>Log&nbsp;out</a>
    {:else}
        <a href={router.loginUrl} use:inertia>Log&nbsp;in</a>
        <a href={router.registerUrl} use:inertia>Sign&nbsp;up</a>
    {/if}
</nav>

<style lang="postcss">
    nav {
        @apply h-full flex flex-row items-center;
        @apply md:w-full md:flex-col md:items-stretch md:mt-3 md:pr-0;
    }

    nav.hide {
        @apply md:hidden;
    }

    a {
        @apply h-full flex items-center md:w-full p-3 text-gray-200;
        @apply md:border-t md:border-t-header-700;
        @apply transition;
    }

    a:hover {
        @apply bg-header-900;
    }

    span.divider {
        @apply h-[75%] flex items-center md:w-full m-3 md:m-0 text-gray-200;
        @apply border-r border-header-600 md:border-r-0 md:border-t;
    }
</style>
