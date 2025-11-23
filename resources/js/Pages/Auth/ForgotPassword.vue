<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    status: {
        type: String,
        default: '',
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post('/forgot-password', {
        onFinish: () => form.reset('email'),
    });
};
</script>

<template>
    <AuthLayout title="Forgot password">
        <Head title="Forgot password" />

        <h1 class="text-xl text-gray-900 mb-2">
            Forgot password
        </h1>
        <p class="text-sm text-gray-600 mb-4">
            Enter the email you used to create your account. We’ll send you a
            link to reset your password.
        </p>

        <div
            v-if="props.status"
            class="mb-4 rounded-md bg-green-50 px-3 py-2 text-xs text-green-700"
        >
            {{ props.status }}
        </div>

        <form @submit.prevent="submit">
            <div class="space-y-4">
                <div>
                    <InputLabel for="email" value="Email" />
                    <TextInput
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="mt-1 block w-full"
                        autocomplete="email"
                        required
                    />
                    <InputError class="mt-1" :message="form.errors.email" />
                </div>
            </div>

            <div class="mt-8 space-y-3">
                <button
                    type="submit"
                    class="inline-flex w-full items-center justify-center rounded-full bg-purple-600 px-4 py-2 text-sm text-white hover:bg-purple-700 disabled:opacity-60"
                    :disabled="form.processing"
                >
                    <span v-if="!form.processing">Send reset link</span>
                    <span v-else>Sending...</span>
                </button>

                <p class="text-center text-sm text-gray-600">
                    Remembered your password?
                    <Link
                        href="/login"
                        class="text-purple-700 hover:text-purple-900"
                    >
                        Log in
                    </Link>
                </p>
            </div>
        </form>
    </AuthLayout>
</template>
