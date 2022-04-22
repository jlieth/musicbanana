<script lang="ts">
    import Fa from "svelte-fa"
    import { faSearch } from "@fortawesome/free-solid-svg-icons"
</script>

<div>
    <button class="m-3 text-lg">
        <Fa icon={faSearch} />
    </button>
    <input type="search" name="search" placeholder="Search">
</div>

<style lang="postcss">
    div {
        @apply flex md:order-last md:mt-3 cursor-pointer;
        @apply text-header-100 bg-header-800 ring ring-white/20 ring-offset-[-2px];
        @apply hover:bg-header-700 hover:ring-highlight-500;
    }

    input {
        @apply flex-grow pr-2 border-0;
        @apply bg-header-800 focus:ring-0;
    }

    div:hover input {
        @apply bg-header-700;
    }
</style>
