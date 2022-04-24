<!-- based on: https://dev.to/joshnuss/creating-a-sidebar-menu-in-svelte-ih2 -->
<script>
    export let open = false
</script>

<div class="foo" on:click={() => (open = !open)}>
    <svg viewBox="0 0 32 32" class:open>
        <line id="top" x1="0" y1="6" x2="32" y2="6" transform-origin="16 6" />
        <line id="middle" x1="0" y1="16" x2="32" y2="16" />
        <line id="bottom" x1="0" y1="26" x2="32" y2="26" transform-origin="16 26" />
    </svg>
</div>

<style>
   div {
        @apply !hidden md:!inline px-3 py-2 rounded;
        @apply bg-header-700;
        @apply ring ring-header-500 ring-offset-[-2px];
        @apply transition;
    }

    div:hover {
        @apply bg-header-900 ring-header-700;
    }

    svg {
        @apply h-6 w-6;
    }

    svg line {
        stroke: currentColor;
        stroke-width: 3;
        transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
    }

    .open #top {
        transform: translateY(10px) rotate(45deg);
    }

    .open #middle {
        opacity: 0;
    }

    .open #bottom {
        transform: translateY(-10px) rotate(-45deg);
    }
</style>
