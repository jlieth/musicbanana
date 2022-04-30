<script lang="ts">
    import { page } from "@inertiajs/inertia-svelte"
    import { BorderAlert } from "flowbite-svelte";
    import Header from "@/Layout/Header/Header.svelte"

    $: console.log($page.props)
    $: flash = $page.props.flash
</script>

<Header />
<div id="wrapper">
    <slot name="sidebar">
        <aside>
            <ul class="w-full">
                <li>foo</li>
                <li>bar</li>
            </ul>
        </aside>
    </slot>

    <main>
        <slot name="flash">
            <div class="flash">
                {#each flash.error as error}
                <BorderAlert alertId="border-alert-2" color="red" closeBtn>
                    {error}
                </BorderAlert>
                {/each}
                {#each flash.success as success}
                <BorderAlert alertId="border-alert-2" color="green" closeBtn>
                    {success}
                </BorderAlert>
                {/each}
            </div>
        </slot>
        <slot name="content">
            <h1>Symfony + Svelte = &lt;3</h1>

            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua. Urna et pharetra pharetra massa massa ultricies mi quis hendrerit. Ac turpis
                egestas integer eget aliquet. Turpis egestas integer eget aliquet. Feugiat pretium nibh ipsum consequat
                nisl vel pretium lectus. Sapien pellentesque habitant morbi tristique senectus. Sollicitudin aliquam
                ultrices sagittis orci a scelerisque purus. Augue neque gravida in fermentum et sollicitudin. A
                pellentesque sit amet porttitor eget. Morbi tristique senectus et netus et. Pretium quam vulputate
                dignissim suspendisse in est ante in nibh. Bibendum ut tristique et egestas quis ipsum suspendisse
                ultrices gravida. Nulla aliquet porttitor lacus luctus accumsan tortor posuere ac.
            </p>

            <p>
                Purus faucibus ornare suspendisse sed nisi lacus sed. Nulla malesuada pellentesque elit eget gravida
                cum. Nibh cras pulvinar mattis nunc sed blandit libero volutpat sed. Quam nulla porttitor massa id.
                Imperdiet massa tincidunt nunc pulvinar. Lacus suspendisse faucibus interdum posuere lorem. Nulla
                posuere sollicitudin aliquam ultrices sagittis orci a scelerisque purus. Ac placerat vestibulum lectus
                mauris ultrices eros. Enim facilisis gravida neque convallis a. Venenatis tellus in metus vulputate eu
                scelerisque felis imperdiet proin.
            </p>

            <p>
                Sit amet est placerat in egestas erat imperdiet sed. Enim diam vulputate ut pharetra sit amet aliquam
                id. Sapien faucibus et molestie ac feugiat sed lectus vestibulum mattis. Amet nulla facilisi morbi
                tempus iaculis urna. Egestas sed sed risus pretium quam. Amet nulla facilisi morbi tempus iaculis. Leo
                in vitae turpis massa sed elementum tempus egestas. Non sodales neque sodales ut etiam sit amet nisl
                purus. Molestie nunc non blandit massa enim nec dui nunc mattis. Neque egestas congue quisque egestas
                diam. Quisque egestas diam in arcu cursus euismod.
            </p>

            <p>
                Feugiat nibh sed pulvinar proin. Amet dictum sit amet justo donec enim diam. Habitant morbi tristique
                senectus et netus et malesuada. Elementum tempus egestas sed sed risus pretium quam vulputate. Nisl vel
                pretium lectus quam id leo in. Ullamcorper dignissim cras tincidunt lobortis feugiat vivamus at augue.
                Senectus et netus et malesuada. In dictum non consectetur a erat nam at lectus. Faucibus scelerisque
                eleifend donec pretium vulputate sapien nec. Consectetur libero id faucibus nisl tincidunt eget. Tempor
                orci eu lobortis elementum nibh tellus molestie nunc non. Feugiat vivamus at augue eget. Scelerisque in
                dictum non consectetur a erat nam at lectus. Ut tristique et egestas quis ipsum.
            </p>

            <p>
                In mollis nunc sed id semper risus. Facilisis sed odio morbi quis commodo odio. Ornare arcu dui vivamus
                arcu felis bibendum ut tristique et. Mattis enim ut tellus elementum sagittis vitae et. Et pharetra
                pharetra massa massa ultricies mi quis. Pulvinar elementum integer enim neque volutpat. Consectetur
                adipiscing elit ut aliquam purus sit. Risus ultricies tristique nulla aliquet enim tortor. Adipiscing
                enim eu turpis egestas pretium. Posuere morbi leo urna molestie at elementum eu. Vitae ultricies leo
                integer malesuada nunc vel risus commodo viverra. Congue mauris rhoncus aenean vel elit. Sem nulla
                pharetra diam sit amet nisl suscipit. Sed enim ut sem viverra aliquet eget sit amet tellus. Nibh tellus
                molestie nunc non blandit. Iaculis eu non diam phasellus vestibulum. Viverra aliquet eget sit amet
                tellus cras adipiscing enim eu. Lacus suspendisse faucibus interdum posuere lorem ipsum.
            </p>
        </slot>
    </main>

</div>

<style>
    :global(#wrapper) {
        @apply text-header-800;
    }

    #wrapper {
        @apply max-w-xxl mx-auto my-2 md:my-0 p-5;
        @apply flex flex-row md:flex-col;
        @apply border border-gray-500 border-opacity-50 md:border-0;
        @apply bg-gray-100;
    }

    aside {
        @apply max-w-[250px] w-full grow-0 shrink-0;
    }

    main {
        @apply w-full grow shrink;
    }

    h1 {
        text-align: center;
        color: #d26d26;
    }
</style>
