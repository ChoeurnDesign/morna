<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    status: {
        type: String,
        default: '',
    },
    canResetPassword: {
        type: Boolean,
        default: true,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};

const loginWithGoogle = () => {
    window.location.href = '/auth/google/redirect';
};
</script>

<template>
    <AuthLayout title="Log in">
        <Head title="Log in" />

        <h1 class="text-xl text-gray-900 mb-2">
            Log in
        </h1>

        <p class="text-sm text-gray-600 mb-4">
            Use your email and password or continue with Google.
        </p>

        <div
            v-if="props.status"
            class="mb-4 rounded-md bg-green-50 px-3 py-2 text-xs text-green-700"
        >
            {{ props.status }}
        </div>

        <!-- Google button -->
        <div class="space-y-3 mb-6">
            <button
                type="button"
                class="inline-flex w-full items-center justify-center gap-2 rounded-full border border-gray-200 bg-white px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                @click="loginWithGoogle"
            >
                <span
                    class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-white"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 48 48"
                        class="h-4 w-4"
                    >
                        <path
                            fill="#EA4335"
                            d="M24 9.5c3.54 0 6 1.54 7.38 2.84l5.42-5.3C33.64 3.36 29.3 1.5 24 1.5 14.82 1.5 7.09 6.98 3.8 14.7l6.74 5.23C11.73 14.14 17.32 9.5 24 9.5z"
                        />
                        <path
                            fill="#34A853"
                            d="M46.5 24.5c0-1.62-.15-3.18-.44-4.68H24v9.06h12.65c-.55 2.96-2.22 5.47-4.73 7.16l7.43 5.78C43.86 37.62 46.5 31.56 46.5 24.5z"
                        />
                        <path
                            fill="#4A90E2"
                            d="M10.54 28.93A14.48 14.48 0 0 1 9.5 24c0-1.7.29-3.34.8-4.88l-6.74-5.23C2.19 16.62 1.5 20.22 1.5 24c0 3.73.67 7.3 1.92 10.53l7.12-5.6z"
                        />
                        <path
                            fill="#FBBC05"
                            d="M24 46.5c5.85 0 10.77-1.93 14.36-5.24l-7.43-5.78C29.3 36.69 26.86 37.5 24 37.5c-6.68 0-12.27-4.64-14.06-10.93l-7.12 5.6C7.09 41.02 14.82 46.5 24 46.5z"
                        />
                    </svg>
                </span>
                <span class="text-sm">
                    Continue with Google
                </span>
            </button>

            <div class="flex items-center gap-2 text-xs text-gray-400">
                <div class="h-px flex-1 bg-gray-200" />
                <span>or continue with email</span>
                <div class="h-px flex-1 bg-gray-200" />
            </div>
        </div>

        <form @submit.prevent="submit">
            <!-- Group: fields + remember/forgot -->
            <div class="space-y-4">
                <!-- Email -->
                <div>
                    <InputLabel for="email" value="Email" />
                    <TextInput
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="mt-1 block w-full"
                        autocomplete="username"
                        required
                    />
                    <InputError class="mt-1" :message="form.errors.email" />
                </div>

                <!-- Password -->
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

                <!-- Remember + forgot -->
                <div class="flex items-center justify-between space-y-3">
                    <label class="inline-flex items-center">
                        <Checkbox
                            v-model:checked="form.remember"
                            name="remember"
                        />
                        <span class="ms-2 text-sm text-gray-600">
                            Remember me
                        </span>
                    </label>

                    <Link
                        v-if="props.canResetPassword"
                        href="/forgot-password"
                        class="text-sm text-purple-700 hover:text-purple-900"
                    >
                        Forgot password?
                    </Link>
                </div>
            </div>

            <!-- Actions: Login then "Don't have account" -->
            <div class="mt-12 space-y-3">
                <button
                    type="submit"
                    class="inline-flex w-full items-center justify-center rounded-full bg-purple-600 px-4 py-2 text-sm text-white hover:bg-purple-700 disabled:opacity-60"
                    :disabled="form.processing"
                >
                    <span v-if="!form.processing">Log in</span>
                    <span v-else>Logging in...</span>
                </button>

                <p class="text-center text-sm text-gray-600">
                    Don't have an account?
                    <Link
                        href="/register"
                        class="text-purple-700 hover:text-purple-900"
                    >
                        Register
                    </Link>
                </p>
            </div>
        </form>
    </AuthLayout>
</template>
