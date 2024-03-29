<script lang="ts">
    import { EyeOffIconSolid, HeartIconSolid, HeartIconOutline } from "@codewithshin/svelte-heroicons"
    import { inertia, page } from "@inertiajs/inertia-svelte"
    import TimeAgo from "javascript-time-ago"

    import router from "@/router"
    import type { RecentTrack } from "@/types"

    const timeAgo = new TimeAgo("en-US")
    let items: RecentTrack[] = $page.props.recentTracks
</script>


<div class="recentList">
    {#each items as item}
    {@const artistUrl = router.artistUrl(item.artist_name)}
    {@const trackUrl = router.trackUrl(item.artist_name, item.track_title)}

    <div class="listen">
        <div class="loved">
            {#if item.loved}
            <HeartIconSolid className="h-6 w-6 text-red-700" />
            {:else}
            <HeartIconOutline className="h-6 w-6 text-gray-400" />
            {/if}
        </div>
        <div class="info">
            <a class="track" href={trackUrl} use:inertia>{item.track_title}</a>
            <a class="artist" href={artistUrl} use:inertia>{item.artist_name}</a>
            <span class="timestamp" title="{item.timestamp}">
                {timeAgo.format(new Date(item.timestamp))}
            </span>
        </div>
    </div>
    {:else}
        <p>No listens in this time period</p>
    {/each}
</div>

<style lang="postcss">
    div.recentList {
        @apply w-full max-w-md shadow-lg;
    }

    div.listen {
        @apply flex flex-row p-2 items-center gap-x-1;
        @apply border-b border-opacity-50 border-gray-400;
        @apply text-sm;
    }

    div.loved {
        @apply md:order-2;
    }

    div.info {
        @apply md:order-1 w-full flex flex-row md:flex-col;
    }

    a.track {
        @apply w-[60%] md:w-full grow-0 shrink truncate md:whitespace-normal font-bold;
    }

    a.artist {
        @apply w-[40%] md:w-full grow-0 shrink truncate md:whitespace-normal;
    }

    span.timestamp {
        @apply md:w-full shrink-0 text-gray-500;
    }
</style>
