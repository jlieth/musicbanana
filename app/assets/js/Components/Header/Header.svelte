<script lang="ts">
    import { page } from "@inertiajs/inertia-svelte"
    import Avatar from "@/Components/Header/Avatar.svelte"
    import Hamburger from "@/Components/Header/Hamburger.svelte"
    import Logo from "@/Components/Header/Logo.svelte"
    import Nav from "@/Components/Header/Nav.svelte"

    $: user = $page.props.user
    let open = false
</script>

<header class="primary">
    <div>
        <!-- musicbanana text -->
        <Logo />

        <!-- spacer element (pushes following elements to the right) -->
        <span class="spacer w-full grow shrink md:hidden"></span>

        <!-- Hamburger button -->
        <Hamburger bind:open />

        <!-- main site navigation -->
        <Nav bind:open />

        <!-- show user avatar -->
        {#if user}
        <Avatar class="ml-4 md:ml-0 md:order-first" />
        {/if}
    </div>
</header>

<style lang="postcss">
    header {
        @apply w-full h-16 shadow-xl;
        @apply bg-gradient-to-b from-header-800 via-header-800 to-header-900;
    }

    :global(header.primary) {
        @apply text-gray-200;
    }

    div {
        @apply w-full max-w-xxl h-full mx-auto flex flex-row items-center justify-between;
    }

    @media screen(md) {
        header {
            @apply h-fit flex-col items-stretch;
        }

        div {
            @apply flex-wrap p-3;
        }
    }
</style>
