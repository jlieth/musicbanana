<script lang="ts">
    import Fa from "svelte-fa"
    import { faSearch } from "@fortawesome/free-solid-svg-icons"

    export let hide: boolean = true
</script>

<div class:hide class="outer">
    <div class="inner">
        <button class="m-3 text-lg">
            <Fa icon={faSearch} />
        </button>
        <input type="search" name="search" placeholder="Search">
    </div>
</div>

<style lang="postcss">
    .outer { @apply w-full p-2 flex bg-header-900 text-header-100 cursor-pointer; }

    .outer.hide { @apply !hidden; }

    .inner {
        @apply w-full flex rounded;
        @apply bg-header-800 border border-highlight-500;
        @apply hover:bg-header-700;
    }

    input {
        @apply flex-grow pr-2;
        @apply bg-header-800 focus:outline-none;
    }

    .inner:hover input {
        @apply bg-header-700;
    }
</style>
