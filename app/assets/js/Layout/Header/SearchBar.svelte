<script lang="ts">
    import { SearchIconSolid } from "@codewithshin/svelte-heroicons"
</script>

<div>
    <SearchIconSolid className="w-6 h-6 ml-2" />
    <input type="search" name="search" placeholder="Search">
</div>

<style lang="postcss">
    div {
        @apply flex items-center md:order-last md:mt-3 cursor-pointer;
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
