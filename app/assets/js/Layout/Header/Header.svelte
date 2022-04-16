<script lang="ts">
    import Fa from "svelte-fa"
    import { faSearch } from "@fortawesome/free-solid-svg-icons"
    import Button from "@/Layout/Header/Button.svelte"
    import Hamburger from "@/Layout/Header/Hamburger.svelte"
    import Logo from "@/Layout/Header/Logo.svelte"
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
    <nav class:hide={!isNavOpen}>
        <Button class="ml-2 md:ml-0">
            <a href="/">Button</a>
        </Button>
        <Button class="ml-2 md:ml-0">
            <a href="/">Link</a>
        </Button>
    </nav>
</header>

<SearchBar bind:hidden={isSearchHidden} />

<style lang="postcss">
    header {
        @apply w-full h-20 flex flex-row bg-header-900;
    }

    header > div {
        @apply w-full flex flex-row items-center justify-between px-3;
    }

    nav {
        @apply flex flex-row items-center pr-3;
    }

    @media screen(md) {
        header {
            @apply h-fit flex-col items-stretch;
        }

        header > div {
            @apply py-3;
        }

        nav {
            @apply w-full flex-col items-stretch pr-0;
        }

        nav.hide {
            @apply hidden;
        }
    }
</style>
