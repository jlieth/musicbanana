<script lang="ts">
    import { inertia, page } from "@inertiajs/inertia-svelte"
    import router from "@/router"

    let component = $page.component
    let artist = $page.props.artist.name
    let album = $page.props.album
    let trackTitle = $page.props.trackTitle
</script>

<header>
    <div>
        {#if component == "Music/Overview"}
        <h2>{ artist }</h2>
        {:else if component == "Music/Album"}
        <h3>{ artist }</h3>
        <h2>{ album.title }</h2>
        {:else if component == "Music/Track"}
        <h3>{ artist }</h3>
        <h2>{ trackTitle }</h2>
        {/if}
    </div>
    <nav>
        <a href={router.artistUrl(artist)} use:inertia>Overview</a>
        <a href="/">Albums</a>
        <a href="/">Tracks</a>
        <a href="/">Listeners?</a>
        <a href="/">Tags?</a>
    </nav>
</header>

<style lang="postcss">
    header {
        @apply flex flex-row md:flex-col justify-between items-center px-3;
        @apply border-b border-gray-500 border-opacity-50 md:border-0;
    }

    h2 {
        @apply text-3xl font-bold;
    }

    nav {
        @apply md:w-full flex flex-row gap-2 md:overflow-scroll;
    }

    a {
        @apply px-2 py-4;
    }

    a:hover {
        box-shadow: inset 0 -3px theme("colors.highlight.500");
    }
</style>
