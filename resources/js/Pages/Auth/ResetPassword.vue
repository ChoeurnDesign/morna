<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    email: {
        type: String,
        default: '',
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post('/reset-password', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthLayout title="Reset password">
        <Head title="Reset password" />

        <h1 class="text-xl text-gray-900 mb-2">
            Reset password
        </h1>
        <p class="text-sm text-gray-600 mb-6">
            Choose a new password for your Morna Mulbeery account.
        </p>

        <form @submit.prevent="submit">
            <div class="space-y-4">
                <!-- Email -->
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

                <!-- Password -->
                <div>
                    <InputLabel for="password" value="New password" />
                    <TextInput
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full"
                        autocomplete="new-password"
                        required
                    />
                    <InputError class="mt-1" :message="form.errors.password" />
                </div>

                <!-- Confirm -->
                <div>
                    <InputLabel
                        for="password_confirmation"
                        value="Confirm password"
                    />
                    <TextInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        autocomplete="new-password"
                        required
                    />
                    <InputError
                        class="mt-1"
                        :message="form.errors.password_confirmation"
                    />
                </div>
            </div>

            <div class="mt-8 space-y-3">
                <button
                    type="submit"
                    class="inline-flex w-full items-center justify-center rounded-full bg-purple-600 px-4 py-2 text-sm text-white hover:bg-purple-700 disabled:opacity-60"
                    :disabled="form.processing"
                >
                    <span v-if="!form.processing">Update password</span>
                    <span v-else>Updating...</span>
                </button>

                <p class="text-center text-sm text-gray-600">
                    Remember your password?
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
