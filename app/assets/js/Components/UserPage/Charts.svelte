<script lang="ts">
    import { page } from "@inertiajs/inertia-svelte"

    import type { ArtistCharts, AlbumCharts, TrackCharts } from "@/types"

    export let chartType: "artist" | "album" | "track"

    let topArtists: ArtistCharts[] = $page.props.topArtists
    let topAlbums: AlbumCharts[] = $page.props.topAlbums
    let topTracks: TrackCharts[] = $page.props.topTracks
</script>

<div class="topList">

    {#if chartType == "artist"}
    {#each topArtists as artist, idx}
    {@const maxCount = topArtists[0].count}
    <div class="row">
        <div class="position">{idx + 1}</div>

        <div class="content">
            <div class="info">
                <a class="artist" href="/">{artist.artist_name}</a>
            </div>

            <a href="/" class="count">
                <span>{artist.count} listens</span>
                <div class="bar" style="width: { artist.count/maxCount * 100 }%"></div>
            </a>
        </div>

    </div>
    {:else}
    <p>No listens in this time period</p>
    {/each}
    {/if}
</div>

<style lang="postcss">
    div.topList {
        @apply w-full max-w-md shadow-lg;
    }

    div.row {
        @apply flex flex-row p-2 items-center gap-x-1;
        @apply border-b border-opacity-50 border-gray-400;
    }

    div.position {
        @apply min-w-[2.5rem] text-center;
    }

    div.content {
        @apply w-full flex flex-row md:flex-col items-center;
    }

    div.info {
        @apply w-1/2 md:w-full flex flex-col grow-0;
    }

    div.info > * {
        @apply truncate;
    }

    a.count {
        @apply w-1/2 md:w-full text-xs grow-0;
    }

    div.bar {
        @apply min-w-[10px] h-4 mt-0.5 rounded-sm;
        @apply bg-gradient-to-r from-highlight-700 to-highlight-600;
    }
</style>
