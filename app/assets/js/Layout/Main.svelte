<script lang="ts">
    import Header from "@/Layout/Header.svelte"
</script>

<div id="wrapper" class="container-lg">
    <Header />

</div>

<style lang="postcss" global>
    :root {
        --highlight-color: #ffc107;
        --highlight-color: #789abc;
        --highlight-color: var(--bs-cyan);
    }

    body {
        background: #e3e3e3;
    }

    a, a:focus, a:hover {
        text-decoration: none;
    }

    a {
        color: #333;
    }

    a:focus, a:hover {
        color: var(--highlight-color);
    }

    #wrapper {
        padding-right: 0;
        padding-left: 0;
        border: 1px solid red;
    }

    @tailwind base;
    @tailwind components;
    @tailwind utilities;
</style>
