<script lang="ts">
    import { inertia, page } from "@inertiajs/inertia-svelte"
    import router from "@/router"
    import Layout from "@/Pages/_Layout.svelte"

    let profileUser = $page.props.profileUser
</script>

<Layout>
    <div slot="content">
        <header>
            <h2>{ profileUser }</h2>
            <nav>
                <a href={router.userOverviewUrl(profileUser)} use:inertia>Overview</a>
                <a href="/">Library</a>
                <a href="/">Reports</a>
                <a href="/">Favorites</a>
                <a href="/">Friends</a>
            </nav>
        </header>

        <slot name="main" />
    </div>

</Layout>

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
