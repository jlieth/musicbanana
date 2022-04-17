<script lang="ts">
    import Fa from "svelte-fa"
    import { faSearch } from "@fortawesome/free-solid-svg-icons"
    import Button from "@/Layout/Header/Button.svelte"
    import Hamburger from "@/Layout/Header/Hamburger.svelte"
    import Logo from "@/Layout/Header/Logo.svelte"
    import Nav from "@/Layout/Header/Nav.svelte"
    import SearchBar from "@/Layout/Header/SearchBar.svelte"

    let isNavOpen = false
    let isSearchHidden = true
</script>

<header>
    <div>
        <!-- Hamburger button -->
        <Hamburger bind:open={isNavOpen} />

        <!-- musicbanana text -->
        <Logo />

        <!--search bar toggle button -->
        <Button class="text-lg">
            <div on:click={() => (isSearchHidden = !isSearchHidden)}>
                <Fa icon={faSearch} style="height: unset;" class="w-6 h-6" />
            </div>
        </Button>

    </div>

    <!-- main site navigation -->
    <Nav bind:open={isNavOpen} />
</header>

<SearchBar bind:hide={isSearchHidden} />

<style lang="postcss">
    header {
        @apply w-full h-20 flex flex-row rounded-t-md bg-header-900;
    }

    header > div {
        @apply w-full flex flex-row items-center justify-between px-3;
    }

    @media screen(md) {
        header {
            @apply h-fit flex-col items-stretch rounded-t-none;
        }

        header > div {
            @apply py-3;
        }
    }
</style>
