<script lang="ts">
    import { inertia, page } from "@inertiajs/inertia-svelte"

    import router from "@/router"
    import type { Charts } from "@/types"

    export let chartType: "album" | "track"

    let items: Charts[] = []

    switch (chartType) {
        case "album":
            items = $page.props.topAlbums
            break
        case "track":
            items = $page.props.topTracks
            break
    }
</script>

<div class="topList">

    {#each items as item, idx}
    {@const maxCount = items[0].count}
    {@const albumUrl = router.albumUrl(item.artist_name, item.album_title)}
    {@const trackUrl = router.trackUrl(item.artist_name, item.track_title)}
    <div class="row">
        <div class="position">{idx + 1}</div>

        <div class="content">
            <div class="info">
                {#if chartType == "album"}
                <a class="title" href={albumUrl} use:inertia>{item.album_title}</a>
                {:else if chartType == "track"}
                <a class="title" href={trackUrl} use:inertia>{item.track_title}</a>
                {/if}
            </div>

            <a href="/" class="count">
                <span>{item.count} listens</span>
                <div class="bar" style="width: { item.count/maxCount * 100 }%"></div>
            </a>
        </div>

    </div>
    {:else}
    {#if chartType == "album"}No albums yet
    {:else if chartType == "track"}No tracks yet
    {/if}
    {/each}
</div>

<style lang="postcss">
    div.topList {
        @apply w-full max-w-md shadow-lg text-sm;
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

    a.title {
        @apply font-bold;
    }

    a.count {
        @apply w-1/2 md:w-full text-xs grow-0;
    }

    div.bar {
        @apply min-w-[10px] h-4 mt-0.5 rounded-sm;
        @apply bg-gradient-to-r from-highlight-700 to-highlight-600;
    }
</style>
