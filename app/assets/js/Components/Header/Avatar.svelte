<script lang="ts">
    import { UserIconOutline } from "@codewithshin/svelte-heroicons"
    import { inertia, page } from "@inertiajs/inertia-svelte"
    import router from "@/router"

    let class_ = ""
    export {class_ as class}

    let user = $page.props.user
    let nameEncoded = encodeURIComponent(user.name)
    let href = router.generate("user_overview", {name: nameEncoded})
</script>

<a {href} class="{class_}" title="Go to your profile" use:inertia>
    <div class="avatar">
        {#if user.avatar}
            <img src="{user.avatar}" alt="avatar">
        {:else}
            <UserIconOutline className="h-6 w-6 rounded-md" />
        {/if}
    </div>

    <div class="name">{user.name}</div>
</a>

<style lang="postcss">
    a {
        @apply h-full flex items-center md:w-full p-3 text-gray-200;
        @apply transition;
    }

    a:hover {
        @apply bg-header-900;
    }

    .avatar {
        @apply w-8 h-8 rounded-md;
        @apply flex items-center justify-center;
        @apply bg-header-700 border border-header-400;
    }

    a:hover .avatar {
        @apply border-highlight-500;
    }

    img {
        @apply rounded-md;
    }

    .name {
        @apply ml-[5px];
    }
</style>
