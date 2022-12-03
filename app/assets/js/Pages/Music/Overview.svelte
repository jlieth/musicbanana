<script lang="ts">
    import Layout from "@/Pages/Music/_Layout.svelte"
    import Charts from "@/Components/MusicPage/Charts.svelte"
</script>

<Layout>
    <main slot="main">
        <section>
            <h3>Top Albums</h3>
            <Charts chartType="album" />
        </section>

        <section>
            <h3>Top Tracks</h3>
            <Charts chartType="track" />
        </section>
    </main>
</Layout>

<style>
    main {
        @apply flex flex-col items-stretch gap-3;
    }

    section {
        @apply p-4;
    }

    h3 {
        @apply mb-2 text-xl;
    }
</style>
