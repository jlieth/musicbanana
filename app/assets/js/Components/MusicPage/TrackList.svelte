<script lang="ts">
    import { page } from "@inertiajs/inertia-svelte"

    import router from "@/router"
    import type { TrackList } from "@/types"

    let tracks: TrackList = $page.props.trackList
    let maxCount: number = $page.props.maxCount
</script>

<div class="trackList">
    {#each tracks as track}
    <div class="row">
        <div class="position">{track.tracknumber}</div>

        <div class="content">
            <div class="info">
                <a class="title" href="/">{track.track_title}</a>
            </div>

            <a href="/" class="count">
                <span>{track.count} listens</span>
                <div class="bar" style="width: { track.count/maxCount * 100 }%"></div>
            </a>
        </div>

    </div>
    {:else}No tracks found
    {/each}
</div>

<style lang="postcss">
    div.trackList {
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
