<script lang="ts">
    import { UserIconOutline } from "@codewithshin/svelte-heroicons"
    import { page } from "@inertiajs/inertia-svelte"

    let class_ = ""
    export {class_ as class}

    $: user = $page.props.user
</script>

<div class="relative h-full flex items-center justify-center {class_}">
    <div class="avatar">
        {#if user.avatar}
            <img src="{user.avatar}" alt="avatar">
        {:else}
            <UserIconOutline className="h-8 w-8 rounded-full" />
        {/if}
    </div>

    <div class="name">
        {user.name}
    </div>

</div>

<style lang="postcss">
    .avatar {
        @apply bg-header-700;
        @apply w-12 h-12 flex items-center justify-center rounded-full;
        @apply ring ring-white/20 ring-offset-[-1px];
    }

    img {
        @apply rounded-full;
    }

    .name {
        @apply max-w-[3.5rem] absolute bottom-2 md:bottom-0;
        @apply text-sm truncate;
        text-shadow:
            -1px -1px 0 #000,
            1px -1px 0 #000,
            -1px 1px 0 #000,
            1px 1px 0 #000;
    }
</style>
