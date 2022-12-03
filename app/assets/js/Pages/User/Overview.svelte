<script lang="ts">
    import Layout from "@/Pages/User/_Layout.svelte"
    import Charts from "@/Components/UserPage/Charts.svelte"
    import RecentList from "@/Components/UserPage/RecentList.svelte"
</script>

<Layout>
    <main slot="main">
        <section>
            <h3>Recent tracks</h3>
            <RecentList />
        </section>

        <section>
            <h3>Top Artists</h3>
            <Charts chartType="artist" />
        </section>

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
