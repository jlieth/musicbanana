<script lang="ts">
    import { page } from "@inertiajs/inertia-svelte"
    import Header from "@/Components/Header/Header.svelte"

    $: console.log($page)
</script>

<Header />
<div id="wrapper">
    <slot name="content" />
</div>

<style>
    :global(#wrapper) {
        @apply text-header-800;
    }

    #wrapper {
        @apply w-1/2 md:w-full max-w-xxl mx-auto my-2 md:my-0;
        @apply border border-gray-500 border-opacity-50 md:border-0;
        @apply bg-gray-100;
    }
</style>
