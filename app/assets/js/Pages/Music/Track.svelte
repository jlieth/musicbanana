<script lang="ts">
    import { inertia, page } from "@inertiajs/inertia-svelte"

    import router from "@/router"
    import Layout from "@/Layout.svelte"
    import SubHeader from "@/Components/MusicPage/SubHeader.svelte"

    let tracks = $page.props.tracks
</script>

<Layout>
    <div slot="subheader">
        <SubHeader />
    </div>

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
        @apply w-[1320px] flex flex-col items-stretch gap-3;
    }

    section {
        @apply p-4;
    }

    h3 {
        @apply mb-2 text-xl;
    }
</style>
