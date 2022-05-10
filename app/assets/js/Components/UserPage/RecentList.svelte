<script lang="ts">
    import { HeartIconSolid, HeartIconOutline } from "@codewithshin/svelte-heroicons"

    let items: Array<{ loved: boolean}> = [
        {loved: false}, {loved: true}, {loved: true}
    ];
    let dateformat = "timesince";
</script>

<table class="recentList w-full max-w-md border-collapse table-auto">
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
            <td class="track"><a href="/">Track</a></td>
            <td class="artist"><a href="/">Artist</a></td>
            <td class="time">
                {#if dateformat == "timesince"}
                    <span class="timestamp momentjs">15 minutes ago</span>
                {:else}
                    <span class="timestamp">g1eü9hü</span>
                {/if}
            </td>
        </tr>
        {:else}
            <p>No scrobbles in this time period</p>
        {/each}
    </tbody>
</table>

<style lang="postcss">
    tr {
        @apply flex p-2 items-center gap-1 md:gap-0 md:flex-wrap;
        @apply border-b border-gray-400;
        @apply text-sm;
    }

    td {
        @apply flex items-center;
    }

    td.loved {
        @apply md:order-2;
    }

    td.track {
        @apply font-bold grow shrink truncate md:order-1;
    }

    td.artist {
        @apply grow shrink truncate md:w-full md:order-3;
    }

    td.time {
        @apply shrink-0 md:w-full md:order-4 text-gray-500;
    }

</style>
