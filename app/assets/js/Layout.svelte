<script lang="ts">
    import { page } from "@inertiajs/inertia-svelte"
    import { BorderAlert } from "flowbite-svelte";
    import Header from "@/Components/Header/Header.svelte"

    $: console.log($page.props)
    $: flash = $page.props.flash
</script>

<Header />
<div id="wrapper">
    <slot name="flash">
        <div class="flash">
            {#each flash.error as error}
            <BorderAlert alertId="border-alert-2" color="red" closeBtn>
                {error}
            </BorderAlert>
            {/each}
            {#each flash.success as success}
            <BorderAlert alertId="border-alert-2" color="green" closeBtn>
                {success}
            </BorderAlert>
            {/each}
        </div>
    </slot>
    <slot name="content" />
</div>

<style>
    :global(#wrapper) {
        @apply text-header-800;
    }

    #wrapper {
        @apply max-w-xxl mx-auto my-2 md:my-0 p-5;
        @apply flex flex-row md:flex-col;
        @apply border border-gray-500 border-opacity-50 md:border-0;
        @apply bg-gray-100;
    }
</style>
