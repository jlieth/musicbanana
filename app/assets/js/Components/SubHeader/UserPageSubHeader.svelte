<script lang="ts">
    import { inertia, page } from "@inertiajs/inertia-svelte"
    import router from "@/router"

    let profileUser = $page.props.profileUser
    let nameEncoded = encodeURIComponent(profileUser)

    let urls = {
        "overview": router.generate("user_overview", {name: nameEncoded}),
    }
</script>

<header>
    <h2>{ profileUser }</h2>
    <nav>
        <a href={urls.overview} use:inertia>Overview</a>
        <a href="/">Library</a>
        <a href="/">Reports</a>
        <a href="/">Favorites</a>
        <a href="/">Friends</a>
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
