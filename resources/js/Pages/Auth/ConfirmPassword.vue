<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post('/confirm-password', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthLayout title="Confirm password">
        <Head title="Confirm password" />

        <h1 class="text-xl text-gray-900 mb-2">
            Confirm password
        </h1>
        <p class="text-sm text-gray-600 mb-6">
            This is a secure area of the site. Please confirm your password
            before continuing.
        </p>

        <form @submit.prevent="submit">
            <div class="space-y-4">
                <div>
                    <InputLabel for="password" value="Password" />
                    <TextInput
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full"
                        autocomplete="current-password"
                        required
                    />
                    <InputError class="mt-1" :message="form.errors.password" />
                </div>
            </div>

            <div class="mt-8 space-y-3">
                <button
                    type="submit"
                    class="inline-flex w-full items-center justify-center rounded-full bg-purple-600 px-4 py-2 text-sm text-white hover:bg-purple-700 disabled:opacity-60"
                    :disabled="form.processing"
                >
                    <span v-if="!form.processing">Confirm password</span>
                    <span v-else>Confirming...</span>
                </button>

                <p class="text-center text-sm text-gray-600">
                    Forgot it?
                    <Link
                        href="/forgot-password"
                        class="text-purple-700 hover:text-purple-900"
                    >
                        Reset password
                    </Link>
                </p>
            </div>
        </form>
    </AuthLayout>
</template>
