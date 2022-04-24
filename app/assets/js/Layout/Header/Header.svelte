<script lang="ts">
    import Hamburger from "@/Layout/Header/Hamburger.svelte"
    import Logo from "@/Layout/Header/Logo.svelte"
    import Nav from "@/Layout/Header/Nav.svelte"

    let open = false
</script>

<header>
    <div>
        <!-- musicbanana text -->
        <Logo />

        <!-- Hamburger button -->
        <Hamburger bind:open />

        <!-- main site navigation -->
        <Nav bind:open />
    </div>
</header>

<style lang="postcss">
    header {
        @apply w-full h-20 shadow-xl;
        @apply bg-gradient-to-b from-header-800 via-header-800 to-header-900;
    }

    :global(header) {
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
