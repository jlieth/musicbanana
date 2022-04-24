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
        @apply w-full h-20 bg-header-900 shadow-xl;
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
