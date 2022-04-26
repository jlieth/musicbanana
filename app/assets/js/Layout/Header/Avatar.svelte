<script lang="ts">
    import { UserIconOutline } from "@codewithshin/svelte-heroicons"
    import { page } from "@inertiajs/inertia-svelte"

    let class_ = ""
    export {class_ as class}

    $: user = $page.props.user
</script>

<a href="/" class="{class_}" title="Go to your profile">
    <div class="avatar">
        {#if user.avatar}
            <img src="{user.avatar}" alt="avatar">
        {:else}
            <UserIconOutline className="h-8 w-8 rounded-full" />
        {/if}
    </div>

    <div class="name">{user.name}</div>
</a>

<style lang="postcss">
    a {
        @apply relative md:ml-3 text-header-200;
    }

    .avatar {
        @apply w-12 h-12 md:w-10 md:h-10 rounded-full;
        @apply flex items-center justify-center;
        @apply bg-header-700;
        @apply ring ring-header-500 ring-offset-[-1px] transition;
    }

    a:hover .avatar {
        @apply ring-highlight-500;
    }

    img {
        @apply rounded-full;
    }

    .name {
        @apply w-12 md:w-10 text-center absolute -bottom-2;
        @apply text-sm truncate;
        text-shadow:
            -1px -1px 0 #000,
            1px -1px 0 #000,
            -1px 1px 0 #000,
            1px 1px 0 #000;
    }
</style>
