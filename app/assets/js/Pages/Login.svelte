<!-- based on https://tailwindcomponents.com/component/simple-login-screen -->

<script lang="ts">
    import { inertia, useForm } from "@inertiajs/inertia-svelte"
    import router from "@/router"
    import Layout from "@/Pages/_Layout.svelte"

    let form = useForm({
        name: null,
        password: null,
    })

    function submit() {
        $form.post(router.loginUrl)
    }
</script>

<Layout>
    <main slot="content">
        <h2>Login</h2>
        <form on:submit|preventDefault={submit}>
            <div>
                <label for="name">Username</label>
                <input id="name" name="name" type="text" bind:value={$form.name} required>
            </div>
            <div>
                <label for="password">Password</label>
                <input id="password" name="password" type="password" bind:value={$form.password} required>
            </div>

            <div class="text-right text-sm">
                <a href="/">Forgot your password?</a>
            </div>

            <div>
                <button type="submit" disabled={$form.processing}>Log In</button>
            </div>

            <div class="mt-6 text-center">
                Don't have an account yet?
                <a href={router.registerUrl} class="underline" use:inertia>Register here</a>
            </div>
        </form>
    </main>
</Layout>

<style lang="postcss">
    main {
        @apply max-w-xs w-full mx-auto p-4;
    }

    h2 {
        @apply mb-10 text-center text-4xl font-bold;
    }

    form {
        @apply flex flex-col gap-4;
    }

    input {
        @apply w-full mt-1 py-2 px-3 rounded-md;
        @apply border border-gray-300 focus:border-highlight-500 focus:outline-none;
        @apply focus:ring focus:ring-highlight-300 focus:ring-opacity-25;
        @apply shadow-sm;
    }

    button {
        @apply w-full px-4 py-2 rounded-md font-semibold text-white;
        @apply bg-highlight-800 hover:bg-highlight-700;
        @apply focus:outline-none focus:border-highlight-500;
        @apply focus:ring focus:ring-highlight-300 focus:ring-opacity-25;
        @apply shadow-md transition;
    }
</style>
