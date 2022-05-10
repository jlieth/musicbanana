<script lang="ts">
    import { EyeOffIconSolid, HeartIconSolid, HeartIconOutline } from "@codewithshin/svelte-heroicons"
    import type { RecentTrack } from "@/types"

    let items: RecentTrack[] = [
        {track: "Track", artist: "Artist", timestamp: "15 minutes ago", loved: false, private: true},
        {track: "Track", artist: "Artist", timestamp: "15 minutes ago", loved: true, private: false},
        {track: "Track", artist: "Artist", timestamp: "15 minutes ago", loved: true, private: true}
    ];
</script>

<table class="recentList">
    <tbody>
        {#each items as item}
        <tr>
            <td class="loved">
                {#if item.loved}
                <HeartIconSolid className="h-6 w-6 text-red-700" />
                {:else}
                <HeartIconOutline className="h-6 w-6 text-gray-400" />
                {/if}
            </td>
            <td class="track"><a href="/">{item.track}</a></td>
            <td class="artist"><a href="/">{item.artist}</a></td>
            <td class="private">
                {#if item.private}
                <div title="Private listen, only visible to you">
                    <EyeOffIconSolid className="h-6 w-6 text-gray-400" />
                </div>
                {/if}
            </td>
            <td class="timestamp">{item.timestamp}</td>
        </tr>
        {:else}
            <p>No scrobbles in this time period</p>
        {/each}
    </tbody>
</table>

<style lang="postcss">
    table {
        @apply w-full max-w-md border-collapse shadow-lg;
    }

    tr {
        @apply flex p-2 items-center gap-x-1 md:flex-wrap;
        @apply border-b border-opacity-50 border-gray-400;
        @apply text-sm;
    }

    td {
        @apply flex items-center;
    }

    td.loved {
        @apply md:order-3;
    }

    td.track {
        @apply font-bold grow shrink truncate md:order-1;
    }

    td.artist {
        @apply grow shrink truncate md:w-full md:order-4;
    }

    td.private {
        @apply h-6 w-6 md:order-2;
    }

    td.timestamp {
        @apply shrink-0 md:w-full md:order-5 text-gray-500;
    }

</style>
