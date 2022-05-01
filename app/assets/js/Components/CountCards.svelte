<script lang="ts">
    import * as Icon from "svelte-feathers"

    export let counts = { listens: 0, artists: 0, albums: 0, tracks: 0 }

    let formatter = new Intl.NumberFormat("en", {notation: "compact", compactDisplay: "short"})
    function format(n: number): String {
        return formatter.format(n)
    }
</script>

<div class="cards">
    <div class="card">
        <Icon.HeadphonesIcon size="75" />
        <div class="text">
            <span class="count">{format(counts.listens)}</span>
            <span>listens</span>
        </div>
    </div>

    <div class="card">
        <Icon.UserIcon size="75" />
        <div class="text">
            <span class="count">{format(counts.artists)}</span>
            <span>artists</span>
        </div>
    </div>

    <div class="card">
        <Icon.DiscIcon size="75" />
        <div class="text">
            <span class="count">{format(counts.albums)}</span>
            <span>albums</span>
        </div>
    </div>

    <div class="card">
        <Icon.MusicIcon size="75" />
        <div class="text">
            <span class="count">{format(counts.tracks)}</span>
            <span>tracks</span>
        </div>
    </div>
</div>

<style>
    .cards {
        @apply mx-auto flex flex-col md:flex-row md:flex-wrap items-stretch;
        @apply text-header-600;
    }

    .card {
        @apply shrink flex flex-row items-center gap-2 py-3 md:px-3;
    }

    .card .text {
        @apply flex flex-col items-center;
        @apply text-2xl md:text-lg font-bold;
    }

    .card .count {
        @apply text-4xl md:text-2xl text-highlight-700;
    }
</style>
