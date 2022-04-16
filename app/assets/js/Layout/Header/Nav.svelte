<script lang="ts">
    import Button from "@/Layout/Header/Button.svelte"

    export let open = false
</script>

<nav class:hide={!open}>
    <Button class="ml-2 md:ml-0">
        <a href="/">Button</a>
    </Button>
    <Button class="ml-2 md:ml-0">
        <a href="/">Link</a>
    </Button>
</nav>

<style lang="postcss">
    nav { @apply flex flex-row items-center pr-3; }

    @media screen(md) {
        nav { @apply w-full flex-col items-stretch pr-0; }
        nav.hide { @apply hidden; }
    }
</style>
