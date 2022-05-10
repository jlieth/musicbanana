<script lang="ts">
    import Layout from "@/Layout.svelte"
    import SubHeader from "@/Components/SubHeader/UserPageSubHeader.svelte"
    import RecentList from "@/Components/UserPage/RecentList.svelte"
</script>

<Layout>
    <div slot="subheader">
        <SubHeader />
    </div>

    <main slot="main">
        <section>
            <h3>Recent tracks</h3>
            <RecentList />
        </section>
    </main>
</Layout>

<style>
    main {
        @apply w-[1320px] flex flex-col items-stretch gap-3;
    }

    section {
        @apply p-4;
    }

    h3 {
        @apply mb-2 text-xl;
    }


</style>
