<script lang="ts">
    import Layout from "@/Layout.svelte"
</script>

<Layout>
    <main slot="main">
        Hi
    </main>
</Layout>

<style>
    main {
        @apply flex flex-row md:flex-col items-stretch gap-3 p-3 md:p-0;
    }
</style>
