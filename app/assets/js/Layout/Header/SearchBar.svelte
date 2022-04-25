<script lang="ts">
    import { SearchIconSolid } from "@codewithshin/svelte-heroicons"

    let class_ = ""
    export {class_ as class}
</script>

<div class={class_}>
    <SearchIconSolid className="w-6 h-6 ml-2" />
    <input type="search" name="search" placeholder="Search">
</div>

<style lang="postcss">
    div {
        @apply flex items-center md:order-last md:mt-3 rounded;
        @apply bg-header-900 ring ring-white/20 ring-offset-[-2px];
        @apply hover:bg-header-700 hover:ring-highlight-500;
        @apply transition;
    }

    input {
        @apply flex-grow pr-2 border-0 rounded;
        @apply bg-header-900 focus:ring-0;
        @apply transition;
    }

    div:hover input {
        @apply bg-header-700;
    }
</style>
