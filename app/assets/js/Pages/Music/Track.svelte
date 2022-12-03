<script lang="ts">
    import { inertia, page } from "@inertiajs/inertia-svelte"

    import router from "@/router"
    import Layout from "@/Pages/_Layout.svelte"

    let tracks = $page.props.tracks
</script>

<Layout>
    <main slot="main">
        <section>
            <h3>Featured on</h3>

            {#each tracks as track}
            <p><a href={router.albumUrl(track.artist_name, track.album_title)} use:inertia>{ track.album_title }</a></p>
            {/each}
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
