<script lang="ts">
    import Layout from "@/Pages/Music/_Layout.svelte"
    import TrackList from "@/Components/MusicPage/TrackList.svelte"
</script>

<Layout>
    <main slot="main">
        <section>
            <h3>Tracks</h3>
            <TrackList />
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
